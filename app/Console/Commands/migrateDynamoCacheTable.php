<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Aws\DynamoDb;

class migrateDynamoCacheTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:dynamo-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a table for the DynamoDB session and cache drivers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $awsConfig = [
            'version' => '2012-08-10',
            'region'  => config('cache.stores.dynamodb.region'),
            'credentials' => [
                'key'    => config('cache.stores.dynamodb.key'),
                'secret' => config('cache.stores.dynamodb.secret'),
            ],
            'endpoint' => config('cache.stores.dynamodb.endpoint'),
        ];
        $tableName = config('cache.stores.dynamodb.table');

        try {

            if (env('APP_ENV') != 'local') {
                $this->error(
                    "Please create production tables using the AWS Management Console."
                );
            }

            $client = new DynamoDb\DynamoDbClient($awsConfig);

            $client->createTable([
                'TableName' => $tableName,
                'AttributeDefinitions' => [
                    [
                        'AttributeName' => 'key',
                        'AttributeType' => 'S',
                    ],
                ],
                'KeySchema' => [
                    [
                        'AttributeName' => 'key',
                        'KeyType' => 'HASH',
                    ],
                ],
                'ProvisionedThroughput' => [
                    'ReadCapacityUnits'  => 1,
                    'WriteCapacityUnits' => 1,
                ],
            ]);

            $this->info("local DynamoDB cache table: \"{$tableName}\" created successfully.");

        } catch (\Exception $e) {

            $this->error('Something went wrong!: ' . $e->getMessage());

        }
    }
}
