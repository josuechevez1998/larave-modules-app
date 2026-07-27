# Tenant attribute encryption

> Fuente: https://tenancyforlaravel.com/docs/v3/tenant-attribute-encryption
> Exportado: 2026-07-03 20:56 UTC

# Tenant attribute encryption

To encrypt attributes on the Tenant model, store them in [custom columns](https://tenancyforlaravel.com/docs/v3/tenants/#custom-columns) and cast the attributes to `'encrypted'`, or your custom encryption cast.

For example, we'll encrypt the tenant's database credentials – `tenancy_db_username` and `tenancy_db_password`. We need to create custom columns for these attributes, because by default, they are stored in the virtual `data` column.

- Add custom columns to the tenants table (we recommend making the string size at least 512 characters, so the string is capable of containing the encrypted data, they also need to be `nullable` since they are filled after creation):

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();

            // Your custom columns
            $table->string('tenancy_db_username', 512)->nullable();
            $table->string('tenancy_db_password', 512)->nullable();

            $table->timestamps();
            $table->json('data')->nullable();
        });
    }
}
```

- Define the custom columns on the Tenant model:

```
public static function getCustomColumns(): array
{
    return [
        'id',
        'tenancy_db_username',
        'tenancy_db_password',
    ];
}
```

- Then define casts for the attributes on the model (using [Laravel's encrypted casts](https://laravel.com/docs/9.x/eloquent-mutators#encrypted-casting), or your custom casts):

```
protected $casts = [
    'tenancy_db_username' => 'encrypted',
    'tenancy_db_password' => 'encrypted',
];
```

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/tenant-attribute-encryption.blade.md)
