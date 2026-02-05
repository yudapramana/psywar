<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\ServiceAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceTokenController extends Controller
{
    /**
     * Issue service token for internal apps (Admin App)
     */
    public function issue(Request $request)
    {
        // ==================================================
        // 1️⃣ VALIDATION
        // ==================================================
        $data = $request->validate([
            'service'   => 'required|string|max:100',
            'abilities' => 'required|array|min:1',
            'abilities.*' => 'string',
        ]);

        // OPTIONAL: hard allowlist abilities
        $allowedAbilities = [
            'read-payment-proof',
        ];

        foreach ($data['abilities'] as $ability) {
            if (! in_array($ability, $allowedAbilities)) {
                throw ValidationException::withMessages([
                    'abilities' => "Ability '{$ability}' is not allowed.",
                ]);
            }
        }

        // ==================================================
        // 2️⃣ UPSERT SERVICE ACCOUNT
        // ==================================================
        return DB::transaction(function () use ($data) {

            $service = ServiceAccount::firstOrCreate([
                'name' => $data['service'],
            ]);

            // ==================================================
            // 3️⃣ REVOKE OLD TOKENS (OPTIONAL BUT RECOMMENDED)
            // ==================================================
            // Pastikan hanya 1 token aktif per service
            $service->tokens()->delete();

            // ==================================================
            // 4️⃣ CREATE NEW TOKEN
            // ==================================================
            $token = $service->createToken(
                $data['service'] . '-token',
                $data['abilities']
            );

            // ==================================================
            // 5️⃣ RESPONSE (TOKEN HANYA DIKIRIM SEKALI)
            // ==================================================
            return response()->json([
                'success' => true,
                'token'   => $token->plainTextToken,
            ]);
        });
    }
}
