<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserProductRequest
 *
 * @package App
 */
class UserProductRequest extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_requested_product';

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
