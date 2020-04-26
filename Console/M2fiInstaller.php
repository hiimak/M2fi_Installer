<?php


    namespace Dyntec\M2fi_Installer\Console;

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;


    class M2fiInstaller extends  Command
    {
        /**
         * Configures arguments and display options for this command.
         *
         * @return void
         */
        protected function configure()
        {
            $this->setName('dyntec:m2if-alter-tables');
            $this->setDescription('Alters tables for M2IF');
            parent::configure();
        }

        /**
         * Executes the command to add products to the database.
         *
         * @param InputInterface $input An input instance
         * @param OutputInterface $output An output instance
         *
         * @return void
         */
        protected function execute(InputInterface $input, OutputInterface $output)
        {

            $sqlArray = [
                "eav_attribute_option_value" => "ALTER TABLE `tablename` ADD INDEX `EAV_ATTRIBUTE_OPTION_VALUE_VALUE` (`value` ASC)",
                "catalog_product_entity_int" => "ALTER TABLE `tablename` ADD INDEX `CATALOG_PRODUCT_ENTITY_INT_VALUE` (`value` ASC)",
                "catalog_product_entity_varchar" => "ALTER TABLE `tablename` ADD INDEX `CATALOG_PRODUCT_ENTITY_VARCHAR_VALUE` (`value` ASC)",
                "catalog_product_entity_decimal" => "ALTER TABLE `tablename` ADD INDEX `CATALOG_PRODUCT_ENTITY_DECIMAL_VALUE` (`value` ASC)",
                "catalog_product_entity_datetime" => "ALTER TABLE `tablename` ADD INDEX `CATALOG_PRODUCT_ENTITY_DATETIME_VALUE` (`value` ASC)",
                "url_rewrite" => [
                    "ALTER TABLE `tablename` ADD INDEX `URL_REWRITE_ENTITY_ID` (`entity_id` ASC)",
                    "ALTER TABLE `tablename` ADD INDEX `URL_REWRIRE_ENTITY_TYPE_ENTITY_ID` (`entity_id` ASC, `entity_type` ASC)"
                ],
                "catalog_product_entity_media_gallery" => "ALTER TABLE `tablename` ADD INDEX `CATALOG_PRODUCT_ENTITY_MEDIA_GALLERY_VALUE` (`value`)"
            ];


            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();

            foreach ($sqlArray as $tableName => $querys) {
                $tableName = $resource->getTableName($tableName); // the table name in this ex
                $stmt = "";
                if (is_array($querys) && !is_string($querys)) {
                    foreach ($querys as $stmt) {
                        $stmt = str_replace('tablename', $tableName, $stmt);
                    }
                } else {
                    $stmt = str_replace('tablename', $tableName, $querys);
                }
                $connection->query($stmt);
                $output->writeln("Indexes successfully created for M2FI");
            }
        }
    }