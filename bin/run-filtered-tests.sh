#!/usr/bin/env bash
set -euo pipefail
cd /home/guanacodev/modular-app
./vendor/bin/sail artisan test --filter='ProfileTest|PasswordUpdateTest|InstitutionIdentityTest'
