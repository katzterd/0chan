<?php
/**
 * @package Scripts
 */
class Script_CleanStorageTrash extends ConsoleScript {

    public function run()
    {
        $i = 0;
        do {
            /** @var StorageTrash[] $trash */
            $trash = Criteria::create(StorageTrash::dao())
                ->addOrder(OrderBy::create('id'))
                ->setLimit(100)
                ->setOffset(100 * $i++)
                ->getList();

            foreach ($trash as $file) {
                $storage = StorageServer::get();
                try {
                    $storage->deleteFile($file->getFilename());
                    StorageTrash::dao()->drop($file);
                } catch (Exception $e) {
                        $this->log($e);
                }
            }

        } while (!empty($trash));
    }

}