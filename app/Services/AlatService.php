<?php
namespace App\Services;

use App\Models\Alat;
use Exception;

class AlatService
{
    use ServiceTrait;

    public function store(array $data): bool
    {
        try {
            $result = Alat::createOrException($data);

            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }

            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findAll(): bool
    {
        try {
            $result = Alat::all();
            $this->setData($result->toArray());
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findById($id): bool
    {
        try {
            $result = Alat::find($id);
            if (!$result) {
                $this->setError("alat id $id not found", 404);
                return false;
            }
            $this->setData($result->toArray());
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function update($id, $data): bool
    {
        try {
            $result = Alat::updateOrException($id, $data);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }

            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function destroy($id)
    {
        try {
            $result = Alat::deleteOrException($id);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }

            $this->setData($result);
            return true;
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