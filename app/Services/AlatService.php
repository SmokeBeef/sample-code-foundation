<?php
namespace App\Services;

use App\Models\Alat;
use Exception;

class AlatService
{
    protected $alat;
    public function __construct(Alat $alat)
    {
        $this->alat = $alat;
    }

    public function store(array $data): bool|Alat
    {
        try {
            $isNameTaken = $this->findByNama($data["nama"]);
            if ($isNameTaken) {
                return false;
            }
            $result = $this->alat->create($data);

            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findAll()
    {
        try {
            $result = $this->alat->all();

            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findById($id)
    {
        try {
            $result = $this->alat->find($id);
            if(!$result){
                return false;
            }
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function update($id, $data)
    {
        try {
            $alat = $this->alat->find($id);
            if(!$alat){
                return false;
            }

            $alat->update($data);

            return $alat;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function destroy($id)
    {
        try {
            $alat = $this->alat->find($id);
            if(!$alat){
                return false;
            }

            $alat->delete();

            return $alat;
        } catch (Exception $err) {
            throw $err;
        }

    }



    protected function findByNama($nama): bool
    {
        try {
            $result = $this->alat->where("nama", $nama)->first();
            return !!$result;
        } catch (Exception $err) {
            throw $err;
        }
    }
}