<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    //     CREATE TABLE favorited (
    //     space_id INT NOT NULL REFERENCES space(id) ON DELETE CASCADE,
    //     customer_id INT NOT NULL REFERENCES customer (id) ON DELETE CASCADE,
    //     is_favorite BOOLEAN NOT NULL DEFAULT FALSE,
    //     PRIMARY KEY (space_id, customer_id)
    // );
    public $timestamps = false;

    protected $table = 'favorited';

    protected $primaryKey = ['space_id', 'customer_id'];

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'space_id',
        'customer_id',
    ];

    // override because of composite key
    public function getKeyForSaveQuery()
    {
        $query = [];
        foreach ($this->primaryKey as $key) {
            $query[$key] = $this->getAttribute($key);
        }

        return $query;
    }

    // Override delete method for composite key
    public function delete()
    {
        if (is_null($this->getKeyName())) {
            throw new \Exception('No primary key defined on model.');
        }

        if (! $this->exists) {
            return false;
        }

        $query = $this->newQueryWithoutScopes()->where('space_id', $this->space_id)->where('customer_id', $this->customer_id);

        return $query->delete();
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

}
