<?php
namespace App\Models;

use App\Core\Model;

class Skill extends Model
{
    public function all(): array
    {
        return $this->query("SELECT * FROM skills ORDER BY name")->fetchAll();
    }
}
