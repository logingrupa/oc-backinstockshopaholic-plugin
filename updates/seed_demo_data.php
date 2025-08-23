<?php namespace Logingrupa\BackInStockShopaholic\Updates;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use October\Rain\Database\Updates\Seeder;

class SeedSpecificUsers extends Seeder
{
    public function run(): void
    {
        // --- Table names
        $sSubscribersTable = 'logingrupa_backinstock_subscribers';
        $sOfferSubsTable   = 'logingrupa_backinstock_offersubscribers';
        $sOffersTable      = 'lovata_shopaholic_offers';

        // Ensure required tables exist
        if (!Schema::hasTable($sSubscribersTable) || !Schema::hasTable($sOfferSubsTable) || !Schema::hasTable($sOffersTable)) {
            return;
        }

        // --- Pick user source: RainLab.User > Lovata.Buddies
        $sUserTable = null;
        if (Schema::hasTable('users')) {
            $sUserTable = 'users';
        } elseif (Schema::hasTable('lovata_buddies_users')) {
            $sUserTable = 'lovata_buddies_users';
        } else {
            return;
        }

        // --- Target emails (as provided)
        $arTargetEmails = [
            'rolands.zeltins@gmai.com',
            'rolliks1@inbox.lv',
            'mrcelherts@gmail.com',
        ];

        // Fetch those users
        $arUsers = DB::table($sUserTable)
            ->select('*')
            ->whereIn('email', $arTargetEmails)
            ->orderBy('id', 'asc')
            ->limit(10)
            ->get();

        if ($arUsers->isEmpty()) {
            // nothing to do if none of the emails exist in the users table(s)
            return;
        }

        // --- Ensure Subscriber rows exist (user_id is UNIQUE / NOT NULL per your schema)
        $arSubscriberIds = [];
        foreach ($arUsers as $obUser) {
            /** @var object $obUser */
            $iUserId = (int) ($obUser->id ?? 0);
            if ($iUserId < 1) {
                continue;
            }

            // Derive name if not present
            $sName = (string) ($obUser->name ?? '');
            if ($sName === '') {
                $sFirst = trim((string) ($obUser->first_name ?? ''));
                $sLast  = trim((string) ($obUser->last_name ?? ''));
                $sName  = trim($sFirst . ' ' . $sLast) ?: ('User ' . $iUserId);
            }

            $sEmail  = isset($obUser->email) ? (string) $obUser->email : null;
            $sLocale = isset($obUser->locale) ? (string) $obUser->locale : null;

            // If a subscriber already exists for this user_id, reuse it
            $obExisting = DB::table($sSubscribersTable)->where('user_id', $iUserId)->first();
            if ($obExisting) {
                $arSubscriberIds[] = (int) $obExisting->id;
                continue;
            }

            // Insert a new subscriber (confirmed for seeding)
            $obNow         = Carbon::now();
            $sUuid         = (string) Str::uuid();
            $sConfirmToken = bin2hex(random_bytes(32)); // 64 chars
            $sUnsubToken   = bin2hex(random_bytes(32));

            $iSubscriberId = DB::table($sSubscribersTable)->insertGetId([
                'user_id'          => $iUserId,
                'uuid'             => $sUuid,
                'email'            => $sEmail,
                'name'             => $sName,
                'locale'           => $sLocale,
                'confirm_token'    => $sConfirmToken,
                'unsub_token'      => $sUnsubToken,
                'confirmed_at'     => $obNow,
                'last_notified_at' => null,
                'created_at'       => $obNow,
                'updated_at'       => $obNow,
            ]);

            $arSubscriberIds[] = (int) $iSubscriberId;
        }

        if (empty($arSubscriberIds)) {
            return;
        }

        // --- Collect 20 real offers (prefer active + quantity > 0)
        $iTargetCount = 20;

        $arPrefOffers = DB::table($sOffersTable)
            ->select('id', 'code', 'active', 'quantity')
            ->where('active', 1)
            ->where('quantity', '>', 0)
            ->orderBy('id', 'asc')
            ->limit($iTargetCount)
            ->get();

        if ($arPrefOffers->count() < $iTargetCount) {
            $arAnyOffers = DB::table($sOffersTable)
                ->select('id', 'code', 'active', 'quantity')
                ->orderBy('id', 'asc')
                ->limit($iTargetCount)
                ->get();

            // Merge unique by id
            $arMerged = collect();
            $arSeen   = [];
            foreach ([$arPrefOffers, $arAnyOffers] as $obSet) {
                foreach ($obSet as $obOffer) {
                    $iOfferId = (int) $obOffer->id;
                    if (!isset($arSeen[$iOfferId])) {
                        $arMerged->push($obOffer);
                        $arSeen[$iOfferId] = true;
                    }
                    if ($arMerged->count() >= $iTargetCount) {
                        break 2;
                    }
                }
            }
            $arOffers = $arMerged;
        } else {
            $arOffers = $arPrefOffers;
        }

        if ($arOffers->isEmpty()) {
            return;
        }

        // --- Create up to 20 OfferSubscriptions, round-robin across the found subscribers
        $arRows      = [];
        $arPairsSeen = [];
        $obNow       = Carbon::now();
        $iSubCount   = count($arSubscriberIds);
        $iIndex      = 0;

        foreach ($arOffers as $obOffer) {
            /** @var object $obOffer */
            $iOfferId = (int) ($obOffer->id ?? 0);
            if ($iOfferId < 1) {
                continue;
            }

            $iSubscriberId = (int) $arSubscriberIds[$iIndex % $iSubCount];
            $iIndex++;

            $sPairKey = $iSubscriberId . '-' . $iOfferId;

            // Skip if already created in-memory
            if (isset($arPairsSeen[$sPairKey])) {
                continue;
            }
            $arPairsSeen[$sPairKey] = true;

            // Skip if pair already exists in DB
            $obExists = DB::table($sOfferSubsTable)
                ->where('subscriber_id', $iSubscriberId)
                ->where('offer_id', $iOfferId)
                ->first();
            if ($obExists) {
                continue;
            }

            // For seeding, default to 'pending' (keeps them eligible for your grouped emails)
            $sStatus    = 'pending';
            $obSentAt   = null;
            $obOpenedAt = null;

            $arRows[] = [
                'subscriber_id' => $iSubscriberId,
                'offer_id'      => $iOfferId,
                'status'        => $sStatus,
                'sent_at'       => $obSentAt,
                'opened_at'     => $obOpenedAt,
                'created_at'    => $obNow,
                'updated_at'    => $obNow,
            ];

            if (count($arRows) >= $iTargetCount) {
                break;
            }
        }

        if (!empty($arRows)) {
            DB::table($sOfferSubsTable)->insert($arRows);
        }
    }
}
