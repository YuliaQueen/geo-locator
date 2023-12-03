<?php

use Doctrine\DBAL\Schema\Schema;

return new class() {
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('locations');

        $table->addColumn('ip', 'string');
        $table->addColumn('hostname', 'string');
        $table->addColumn('continent_code', 'string');
        $table->addColumn('continent_name', 'string');
        $table->addColumn('country_code2', 'string');
        $table->addColumn('country_code3', 'string');
        $table->addColumn('country_name', 'string');
        $table->addColumn('country_capital', 'string');
        $table->addColumn('state_prov', 'string');
        $table->addColumn('district', 'string');
        $table->addColumn('city', 'string');
        $table->addColumn('zipcode', 'string');
        $table->addColumn('latitude', 'float');
        $table->addColumn('longitude', 'float');
        $table->addColumn('is_eu', 'boolean');
        $table->addColumn('calling_code', 'string');
        $table->addColumn('country_tld', 'string');
        $table->addColumn('languages', 'string');
        $table->addColumn('country_flag', 'string');
        $table->addColumn('geoname_id', 'string');
        $table->addColumn('isp', 'string');
        $table->addColumn('connection_type', 'string');
        $table->addColumn('organization', 'string');
        $table->addColumn('asn', 'string');
        $table->addColumn('currency_code', 'string');
        $table->addColumn('currency_name', 'string');
        $table->addColumn('currency_symbol', 'string');
        $table->addColumn('time_zone_name', 'string');
        $table->addColumn('time_zone_offset', 'string');
        $table->addColumn('time_zone_current_time', 'string');
        $table->addColumn('time_zone_current_time_unix', 'string');
        $table->addColumn('time_zone_is_dst', 'boolean');
        $table->addColumn('time_zone_dst_savings', 'integer');

        $table->setPrimaryKey(['ip']);
    }
};