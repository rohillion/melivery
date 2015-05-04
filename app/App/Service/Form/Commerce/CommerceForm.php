<?php

namespace App\Service\Form\Commerce;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\User\UserForm;
use App\Service\Form\AbstractForm;

class CommerceForm extends AbstractForm {

    /**
     * Commerce repository
     *
     * @var \App\Repository\Commerce\CommerceInterface
     */
    protected $commerce;
    protected $userForm;

    public function __construct(ValidableInterface $validator, CommerceInterface $commerce, UserForm $userForm) {
        parent::__construct($validator);
        $this->commerce = $commerce;
        $this->userForm = $userForm;
        $this->messageBag = new MessageBag();
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->commerce->all($columns, $with);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->commerce->create($input);
    }

    /**
     * Update an existing commerce
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        $commerce = $this->commerce->edit($id, $input);

        if ($commerce)
            $this->userForm->step(\Session::get('user.id'), \Config::get('cons.steps.commerce_name.id'));

        return $commerce;
    }

    /**
     * Update an existing commerce
     *
     * @return boolean
     */
    public function delete($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->commerce->edit($id, $input);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->commerce->edit($id, $input);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function find($idCommerce, $columns = array('*'), $entities = array()) {

        return $this->commerce->find($idCommerce, $columns, $entities);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function checkBrandUrl($url) {

        $valid = \Validator::make(
                        array('brandUrl' => $url), array('brandUrl' => 'required|unique:commerce,commerce_url,' . \Session::get('user.id_commerce'))
        );

        if ($valid->fails()) {
            $this->validator->errors = $valid->messages();
            return false;
        }

        return true;
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function uploadImage($file, $conf) {

        if (!$file->isValid()) {
            $this->messageBag->add('error', 'El archivo es demasiado grande. Por favor seleccione una imagen de menos de ' . ini_get('upload_max_filesize') . '.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        $supportedTypes = ['image/jpeg', 'image/png'];

        if (!in_array($file->getMimeType(), $supportedTypes)) {
            $this->messageBag->add('error', 'El archivo tiene un formato incorrecto. Por favor, seleccione una imagen del tipo JPG o PNG.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        try {
            \MeliveryImageManager::upload($file, public_path() . '/' . $conf['path'] . '/' . \Session::get('user.id_commerce'), $conf['name'], $conf['size']['width'], $conf['size']['height']);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            $this->messageBag->add('error', 'Ha ocurrido un error. Por favor, refresque el navegador y vuelva a intentarlo.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            $image = false;
        }

        return $conf['path'] . '/' . \Session::get('user.id_commerce') . '/' . $conf['name'];
    }

}
