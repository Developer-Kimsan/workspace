<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\User;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation { bulkDelete as traitBulkDelete; }
    
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        if (backpack_auth()->user()->role != '1') {
            return abort(404);
        } else {
            CRUD::setModel(User::class);
            CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
            CRUD::setEntityNameStrings('User', 'users');    
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // columns
        $this->crud->addColumns([
            [
                'name'  => 'name', // The db column name
                'label' => 'Name', // Table column heading
                'type'  => 'text',
            ],
            [
                'name'  => 'email', // The db column name
                'label' => 'Email', // Table column heading
                'type'  => 'email',
            ],
            [
                'name'  => 'role', // The db column name
                'label' => 'Role', // Table column heading
                'type'  => 'radio',
                'options'     => [
                    1 => 'Admin',
                    2 => 'User'
                ]
            ],
            [   // select_and_order
                'name'        => 'status',
                'label'       => "Status",
                'type'        => 'radio',
                'options'     => [
                    '1' => 'Active',
                    '0' => 'Deactive'
                ]
            ],
            [
                'name'  => 'created_at', // The db column name
                'label' => 'Created at', // Table column heading
                'type'  => 'datetime',
            ],
            
        ]);
        $this->crud->enableDetailsRow();
        $this->crud->enableExportButtons();
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        // CRUD::setFromDb(); // fields
        $this->crud->addFields([
            [
                'label' => "Name",
                'type' => 'text',
                'name' => 'name',
                'placeholder' => 'username',
                'tab'  => 'Add Users',
            ],
            [
                'label' => "Email",
                'type' => 'email',
                'name' => 'email',
                'tab'  => 'Add Users',
            ],
            [
                'label' => "Password",
                'type' => 'password',
                'name' => 'password',
                'tab'  => 'Add Users',
            ],
            [
                'label' => "Password Comfirmation",
                'type' => 'password',
                'name' => 'password_confirmation',
                'tab'  => 'Add Users',
            ],
            [   // select_and_order
                'name'        => 'role',
                'label'       => "Roles",
                'type'        => 'select_from_array',
                'options'     => ['1' => 'Admin', '2' => 'User'],
                'allows_null' => false,
                'default'     => '2',
                'tab'         => 'Roles',
            ],
            [   // select_and_order
                'name'        => 'status',
                'label'       => "Status",
                'type'        => 'select_from_array',
                'options'     => ['1' => 'Active', '0' => 'Deactive'],
                'allows_null' => false,
                'default'     => '1',
                'tab'         => 'Roles',
            ],
            [   // Upload
                'name'      => 'image',
                'label'     => 'Profile Image',
                'type'      => 'image',
                'upload'    => true,
                'crop'         => true,
                'aspect_ratio' => 1,
                'disk'      => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'tab'       => 'Uploads Profile',
            ],
        ]);
       
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
        // $this->crud->enableTabs();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
