<?php
namespace App\Services;

use App\Models\Alat;
use Exception;

class AlatService
{
    use ServiceTrait;

    public function store(array $data): bool|Alat
    {
        try {
            $isNameTaken = $this->findByNama($data["alat_nama"]);
            if ($isNameTaken) {
                return false;
            }
            $result = Alat::create($data);

            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findAll()
    {
        try {
            $result = Alat::all();
            $this->setData($result->toArray());
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findById($id)
    {
        try {
            $result = Alat::find($id);
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
            $alat = Alat::find($id);
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
            $alat = Alat::find($id);
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
            $result = Alat::where("alat_nama", $nama)->first();
            return !!$result;
        } catch (Exception $err) {
            throw $err;
        }
    }
}