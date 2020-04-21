<?php

namespace App\Model;

class BouquetManager extends AbstractManager
{
    const TABLE = "bouquet";


  /**
   *init this class.
   */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


  /**
   * test
   */
    public function insert(array $bouquet): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (nom, prix, description, saisonnier)
        VALUES (:nom, :prix, :description, :saisonnier)");
        $statement->bindValue('nom', $bouquet['nom'], \PDO::PARAM_STR);
        $statement->bindValue('prix', $bouquet['prix'], \PDO::PARAM_INT);
        $statement->bindValue('description', $bouquet['description'], \PDO::PARAM_STR);
        $statement->bindValue('saisonnier', $bouquet['saisonnier'], \PDO::PARAM_BOOL);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}