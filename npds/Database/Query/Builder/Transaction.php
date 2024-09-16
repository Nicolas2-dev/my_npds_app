<?php


namespace Npds\Database\Query\Builder;

use Npds\Database\Query\Builder as BaseBuilder;
use Npds\Database\Query\Builder\TransactionHaltException;


class Transaction extends BaseBuilder
{

    /**
     * [commit description]
     *
     * @return  [type]  [return description]
     */
    public function commit()
    {
        $this->pdo->commit();

        throw new TransactionHaltException();
    }

    /**
     * [rollback description]
     *
     * @return  [type]  [return description]
     */
    public function rollback()
    {
        $this->pdo->rollBack();

        throw new TransactionHaltException();
    }
    
}
