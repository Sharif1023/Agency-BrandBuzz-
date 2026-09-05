# Optional database smoke checks

Create a **separate** database named, for example, `brandbuzz_agency_test` and import `database/agency.sql` into it. Supply that database through environment variables or a temporary local `.env` and set `APP_ENV=testing`.

```bash
APP_ENV=testing DB_DATABASE=brandbuzz_agency_test php tests/model-smoke.php
```

The script refuses to mutate a database unless its name ends in `_test` and the environment is `testing`. It creates temporary records, exercises publication visibility, uniqueness, contact states and UTF-8 data, and removes those records in a `finally` block. It does not drop tables or remove existing records.

The delivery was additionally checked by executing actual PHP CGI requests with an isolated MariaDB database. Coverage is recorded in `TESTING.md`; browser interaction and live cPanel deployment were not part of that check.
