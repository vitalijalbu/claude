# Atlas Authentication

## Configuration

* Add Atlas Service Provider in bootstrap/providers
* Copy migration `2025_05_30_083129_create_permission_tables` in migrations folder. (with ULID)
* Create `config/atlas.php` with following configuration

```php
return [
    'base_url' => env('ATLAS_BASE_URL'),
    'jwt_issuer' => env('ATLAS_JWT_ISSUER'),
    'jwt_app_audience' => env('ATLAS_APP_AUDIENCE')
];
```

* add environment variables

```env
ATLAS_BASE_URL="https://dev.atlas.theidfactory.com"
ATLAS_JWT_ISSUER="development-atlas.theidfactory.com"
ATLAS_APP_AUDIENCE="idf:portal:form"
```

* aggiungere trait `HasRole` nel model `User`

* aggiungere ruoli nel Database `Role`
