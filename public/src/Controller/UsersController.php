<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\LoginsController;
//use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\I18n\Time;
use SoapClient;
use SoapVar;
use SoapHeader;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */

//App::uses('LoginsController', 'Controller');

class UsersController extends AppController
{
	public $debug = false;
	
	public $paginate = array(
        'limit' => 50
    );
	
	public function initialize()
    {
	
        parent::initialize();
        /*$this->viewBuilder()->layout('dashboard');
		$this->viewBuilder()->layout('dashboard');
        $this->loadComponent('RBruteForce');*/
		$this->loadModel('Users');
		$this->loadModel('Logins');
		$this->set('title', 'Home');
		
		

	}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function home()
    {
        //$users = $this->paginate($this->Users);

        //$this->set(compact('users'));
        //$this->set('_serialize', ['users']);
		
		$this->loadModel('Banners');
		/*$banner = $this->Banners->find()
		->where(['parent_id' => 0,'status'=>'enable'])
		->toArray();*/
		$result = $this->Banners->find('all')->all();
		// Get the first and/or last result.
		$banner = $result->first();
		//$row = $result->last();
		$this->set('title', 'Home');
		$this->set('banner', $banner);
    }
	
	/**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $users = $this->Users->find('all');
		$users->where(['archived'=>'no']);
		/*$this->paginate = ([
	'sortWhitelist' => [
		'Users.username','Users.code','Users.first_name','Users.last_name'
	],
	'order' => ['Users.first_name'=>'asc']
]);*/
		
		$users = $this->paginate($users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
		
		
		
		/*$users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);*/
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$user = $this->Users->newEntity();
        if ($this->request->is('post')) {
			//debug($_POST); die();
			$send_mail = $_POST['send_mail'];
			unset($_POST['send_mail']);
            $user = $this->Users->patchEntity($user, $_POST);
			
			$result = $this->Users->save($user);
			//debug($result);
			
            if ($result) {
				$user_id = $result->id;
				if($send_mail == 1)
				{
					
					/****************************************************/
					/***************Send Mail Code Starts****************/
					/***************************************************/
					$userData = $this->Users->get($user_id, [
						'contain' => []
					]);

					$data['subject'] = 'A&D Portal Log-in Details';
					$data['message'] = '<img src="https://dealers.andweighing.com.au/img/ANDEmailLogo.png"><br/><br/>';
					$data['message'] .= 'Here is the information you need to log into the A&D Dealer Portal.<br/><br/>';
					$data['message'] .= '<table border="1" cellspacing="0" cellpadding="5" width="50%">';
					$data['message'] .= '<tr>';
					$data['message'] .= '<td><strong>COMPANY CODE</strong></td><td>'.$userData->code.'</td>';
					$data['message'] .= '</tr>';
					$data['message'] .= '<tr>';
					$data['message'] .= '<td><strong>USERNAME</strong></td><td>'.$userData->username.'</td>';
					$data['message'] .= '</tr>';
					$data['message'] .= '<tr>';
					$data['message'] .= '<td><strong>PASSWORD</strong></td><td>'.$userData->password.'</td>';
					$data['message'] .= '</tr>';
					$data['message'] .= '</table><br/><br/>';
					$data['message'] .= 'Please retain this information for your records. These details are for you only.<br/><br/>';
					$data['message'] .= 'Should one of your colleagues require access to this portal, please let us know and we can set them up accordingly.<br/><br/>';
					$data['message'] .= 'Should you have any further questions, please do not hesitate to contact us.<br/><br/>';
					$data['message'] .= '<a href="https://dealers.andweighing.com.au/">Click here to log-in now</a><br/><br/>';

					$data['to'] = $userData->email;
					$data['bcc'] = '';
					$data['cc'] = '';

					$email = new Email('default');
					$send1 = $email->from(['sussann@andaustralasia.com.au'=>'A&D Weighing'])
						->emailFormat('html')
						->to($data['to']);    
					if(!empty($data['bcc'])){
						$bcc = explode(',',$data['bcc']);
						$send1 = $email->bcc($bcc);
					}
					if(!empty($data['cc'])){
						$cc = explode(',',$data['cc']);
						$send1 = $email->cc($cc);
					}
					$send1 = $email->subject($data['subject'])->send($data['message']);
					/****************************************************/
					/***************Send Mail Code Ends******************/
					/***************************************************/
					
					
				}
				
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        /*$this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
		$user->archived = 'yes';
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);*/
		
		
		//$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($id, [
            'contain' => []
        ]);
		$this->request->data['archived'] = 'yes';
		$this->request->data['archived_date'] =  new Time();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been deleted.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        }
		return $this->redirect(['action' => 'index']);
    }
	
	public function login()
	{
		$Logins = new LoginsController();
		$this->viewBuilder()->layout('login');
		
		$this->set('title', 'Login');
		
		if ($this->request->is('post')) {
			//debug($_POST); 
            //$count = $this->RBruteForce->getCount();
			//$user = $this->Auth->identify();
			$user = $this->Users->find()
		->where(['username' => $_POST['username'], 'password' => $_POST['password'], 'code' => $_POST['company_code']])
		->toArray();
			//print_r($user);
			//debug($user); die();
			if ($user) {
				
				if($user[0]->archived == 'no')
				{
				$this->Auth->setUser($user);
				debug($_SESSION); //die();
				$logins['user_id']=$_SESSION['Auth']['User'][0]->id;
				$Logins->insert($logins);
				return $this->redirect($this->Auth->redirectUrl());
				}
			}
			$this->Flash->error(__('Incorrect Login'));
        }
	}
	
	

	
	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}
	
	public function manuals()
	{
		
	}
	
	public function priceList()
	{
		
	}
    public function stockenquiry()
    {
        
    }
	 public function partenquiry()
    {
        
    }
	public function reports()
    {
       
    }
	
	public function sendPassword () {
		//echo date('Y-m-d');die;
        
		
		
		$user = $this->Users->get($_REQUEST['id'], [
            'contain' => []
        ]);
		//debug($user);
		//die();
		/*toma@andaustralasia.com.au*/
		
		
		$data['subject'] = 'A&D Portal Log-in Details';
		$data['message'] = '<img src="https://dealers.andweighing.com.au/img/ANDEmailLogo.png" width="111"><br/><br/>';
		$data['message'] .= 'Here is the information you need to log into the A&D Dealer Portal.<br/><br/>';
		$data['message'] .= '<table border="1" cellspacing="0" cellpadding="5" width="50%">';
		$data['message'] .= '<tr>';
		$data['message'] .= '<td><strong>COMPANY CODE</strong></td><td>'.$user->code.'</td>';
		$data['message'] .= '</tr>';
		$data['message'] .= '<tr>';
		$data['message'] .= '<td><strong>USERNAME</strong></td><td>'.$user->username.'</td>';
		$data['message'] .= '</tr>';
		$data['message'] .= '<tr>';
		$data['message'] .= '<td><strong>PASSWORD</strong></td><td>'.$user->password.'</td>';
		$data['message'] .= '</tr>';
		$data['message'] .= '</table><br/><br/>';
		$data['message'] .= 'Please retain this information for your records. These details are for you only.<br/><br/>';
		$data['message'] .= 'Should one of your colleagues require access to this portal, please let us know and we can set them up accordingly.<br/><br/>';
		$data['message'] .= 'Should you have any further questions, please do not hesitate to contact us.<br/><br/>';
		$data['message'] .= '<a href="https://dealers.andweighing.com.au/">Click here to log-in now</a><br/><br/>';
		
		$data['to'] = $user->email;
		$data['bcc'] = '';
		$data['cc'] = '';
		
		//debug($data); die();
		//debug($_SESSION['Auth']); die();

        $email = new Email('default');
        $send1 = $email->from(['sussann@andaustralasia.com.au'=>'A&D Weighing'])
            ->emailFormat('html')
            ->to($data['to']);    
        if(!empty($data['bcc'])){
            $bcc = explode(',',$data['bcc']);
            $send1 = $email->bcc($bcc);
        }
        if(!empty($data['cc'])){
            $cc = explode(',',$data['cc']);
            $send1 = $email->cc($cc);
        }
        $send1 = $email->subject($data['subject'])->send($data['message']);

        
		//$this->set(compact('quote'));
        //$this->set('_serialize', ['quote']);
		
		
		if($send1)
			{
				$result = array('value'=>true);
				$this->set(compact('result'));
			}
			else
			{
				$result = array('value'=>false);
				$this->set(compact('result'));
				$this->set('_serialize', ['result']);
			}
        //return $this->redirect(['action' => 'index/']);
        
	}
	
	public function recentAccess()
	{
		if($this->request->is('get') && isset($_REQUEST['access_number']) && $_REQUEST['access_number']!="")
		{
			
			$users = $this->Logins->find('all', [
        	'fields' => [
				'id' => 'Logins.id',
				'userid' => 'Users.id',
				'code' => 'code',
				'username' => 'username',
				'firstname' => 'first_name',
				'lastname' => 'last_name',
				'logins' => 'COUNT(Logins.user_id)',
				'last_access' => 'MAX(Logins.created)'
        		]]);
			if(isset($_REQUEST['search_string']))
			{
				$users = $users->where(['Logins.created >= (DATE_SUB(CURDATE(), INTERVAL '.$_REQUEST['access_number'].' DAY))  and (  username like "%'.$_REQUEST['search_string'].'%"or   first_name like "%'.$_REQUEST['search_string'].'%" or   last_name like "%'.$_REQUEST['search_string'].'%"  or code like "%'.$_REQUEST['search_string'].'%" ) and archived = "no"']);

			}else{
				$users = $users->where(['Logins.created >= (DATE_SUB(CURDATE(), INTERVAL '.$_REQUEST['access_number'].' DAY)) and archived = "no"']);
				
			}
			$users = $users
			->contain(['Users'])
			->group(['Logins.user_id']);
			
			$this->paginate = ([
	'sortWhitelist' => [
		'username','code','first_name','last_name'
	],
	'order' => ['first_name'=>'asc']
]);
			
		}
		else if($this->request->is('get') && isset($_REQUEST['code']) && $_REQUEST['code']!="" )
		{
			$users = $this->Logins->find('all', [
        'fields' => [
            'id' => 'Logins.id',
			'userid' => 'U.id',
            'code' => 'U.code',
            'username' => 'U.username',
            'firstname' => 'U.first_name',
            'lastname' => 'U.last_name',
            'logins' => 'COUNT(Logins.user_id)',
			'last_access' => 'MAX(Logins.created)'
        ],
        'join' => [
            'table' => 'users as U', 
            'type' => 'LEFT',
            'conditions' => 'Logins.user_id = U.id'
        ],
        'group' => ['Logins.user_id'],
]);
			
			if(isset($_REQUEST['search_string']))
			{
			$users = $users->where(['U.code = "'.$_REQUEST['code'].'" and (  username like "%'.$_REQUEST['search_string'].'%" or first_name like "%'.$_REQUEST['search_string'].'%" or last_name like "%'.$_REQUEST['search_string'].'%" ) and archived = "no"']);

			}else{
				$users = $users->where(['U.code'=> $_REQUEST['code'], 'archived' => 'no']);
				
			}
			
			$this->paginate = ([
	'sortWhitelist' => [
		'username','code','first_name','last_name'
	],
	'order' => ['first_name'=>'asc']
]);
			
		} /*else if($this->request->is('get') && !isset($_REQUEST['access_number'])) {
			$users = $this->Logins->find('all', [
			'fields' => [
				'id' => 'Logins.id',
				'userid' => 'U.id',
				'code' => 'U.code',
				'username' => 'U.username',
				'firstname' => 'U.first_name',
				'lastname' => 'U.last_name',
				'logins' => 'COUNT(Logins.user_id)',
				'last_access' => 'MAX(Logins.created)'
			],
			'join' => [
				'table' => 'users as U', 
				'type' => 'LEFT',
				'conditions' => 'Logins.user_id = U.id'
			],
        	'group' => ['Logins.user_id'],
			]);
			
			if(isset($_REQUEST['search_string']))
			{
			$users = $users->where(['archived = "no" and (username like "%'.$_REQUEST['search_string'].'%" or first_name like "%'.$_REQUEST['search_string'].'%" or last_name like "%'.$_REQUEST['search_string'].'%"  or code like "%'.$_REQUEST['search_string'].'%" ) and archived = "no")']);

			}
			else
			{
				$users = $users->where(['archived = "no"']);
			}
			
		}*/ else {
			$users = $this->Logins->find('all', [
        'fields' => [
            'id' => 'Logins.id',
			'userid' => 'U.id',
            'code' => 'U.code',
            'username' => 'U.username',
            'firstname' => 'U.first_name',
            'lastname' => 'U.last_name',
            'logins' => 'COUNT(Logins.user_id)',
			'last_access' => 'MAX(Logins.created)'
        ],
        'join' => [
            'table' => 'users as U', 
            'type' => 'Right',
            'conditions' => 'Logins.user_id = U.id'
        ],
        'group' => ['U.id'],
		'order' => ['logins'=>'desc']
				
]);
		
			if(isset($_REQUEST['search_string']))
			{
			$users = $users->where(['archived = "no" and (username like "%'.$_REQUEST['search_string'].'%" or first_name like "%'.$_REQUEST['search_string'].'%" or last_name like "%'.$_REQUEST['search_string'].'%"  or code like "%'.$_REQUEST['search_string'].'%" )']);

			}
			else
			{
				$users = $users->where(['archived = "no"']);
			}
			//$users = $users->having(['logins ' => 0]);

			
			$this->paginate = ([
	'sortWhitelist' => [
		'username','code','first_name','last_name','logins'
	],
				'limit' => 500,
	
]);
			
			//echo 'jjjjjjjjjjjjjjjj';
			
		}
		//debug($users);
		
		$users = $this->paginate($users);
		$this->set(compact('users'));
		//debug($users);
	}
	
	public function disabledDealers()
	{
		$users = $this->Users->find('all');
		
		
		if(isset($_REQUEST['search_string']))
			{
			$users->where(['archived = "yes" and ( username like "%'.$_REQUEST['search_string'].'%" or first_name like "%'.$_REQUEST['search_string'].'%" or last_name like "%'.$_REQUEST['search_string'].'%"  or code like "%'.$_REQUEST['search_string'].'%" )']);

			}else
		{
			$users->where(['archived'=>'yes']);
		}
		$this->paginate = ([
	'sortWhitelist' => [
		'Users.username','Users.code','Users.first_name','Users.last_name'
	],
	'order' => ['Users.first_name'=>'asc']
]);
		
		$users = $this->paginate($users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
	}
	
	public function search()
	{
		
		
		
		if(isset($_REQUEST['search_string']))
			{
			
			/*$users = $this->Logins->find('all', [
        	'fields' => [
				'id' => 'Logins.id',
				'userid' => 'Users.id',
				'code' => 'code',
				'username' => 'username',
				'firstname' => 'first_name',
				'lastname' => 'last_name',
				'logins' => 'COUNT(Logins.user_id)',
				'last_access' => 'MAX(Logins.created)'
        		]]);*/
			
			$users = $this->Users->find('all', [
        'fields' => [
			'userid' => 'Users.id',
            'code' => 'Users.code',
            'username' => 'Users.username',
            'firstname' => 'Users.first_name',
            'lastname' => 'Users.last_name',
            'logins' => 'COUNT(Logins.user_id)',
			'last_access' => 'MAX(Logins.created)'
        ],
        'join' => [
            'table' => 'logins as Logins', 
            'type' => 'LEFT',
            'conditions' => 'Users.id = Logins.user_id'
        ],
        'group' => ['Users.id'],
]);
			
			//$users = $this->Users->find('all');
			$users->where(['archived = "no" and ( username like "%'.$_REQUEST['search_string'].'%" or first_name like "%'.$_REQUEST['search_string'].'%" or last_name like "%'.$_REQUEST['search_string'].'%"  or code like "%'.$_REQUEST['search_string'].'%" )']);
			
			//debug($users);
			
			/*$users = $users
			->contain(['Users'])
			->group(['Logins.user_id']);*/
			
			$this->paginate = ([
	'sortWhitelist' => [
		'username','code','first_name','last_name'
	],
	'order' => ['first_name'=>'asc']
]);
			$users = $this->paginate($users);
			$this->set(compact('users'));
        $this->set('_serialize', ['users']);
			}
		
		
		

        	
	}
	
	public function stockSearch()
	{
		//$item = filter_input(INPUT_POST, "itemno");
		$debug = false;
		$item = $_POST['itemno'];
		 //Set up SOAP Objects 
        //include('vantageSoap.php');
        $params = array('CompanyID' => 'AND', 'partNum' => "$item");
        $vantageSoap = new vantageSoap('GetByID', $params);
        $vantageSoap->setup();

        //Make SOAP Call
        $result = $vantageSoap->call();
		//dedug($result);
		
        
        if (empty($result)) {
            //$ret[] = array('evisible' => 'visible',   'visible' => 'hidden');
			$ret = array('value'=>false);
			$this->set(compact('result'));
			$this->set('_serialize', ['result']);
        } else {
            $data_part = $result['GetByIDResult']['PartDataSet']['Part'];
            $data_partwhse = $result['GetByIDResult']['PartDataSet']['PartWhse'];
           
            $data_size = sizeof($data_partwhse);
            $qtyOnHand = 0;
            $allocatedQty = 0;
            for ($i = 0; $i < $data_size; $i++) {
                //Exclude Showroom Warehouses from results
                if (!strpos($data_partwhse[$i]['WarehouseCode'], 'SR')) {
                    $qtyOnHand += $data_partwhse[$i]['OnHandQty'];
                    $allocatedQty += $data_partwhse[$i]['AllocQty'];
                }//End If
            }//End For
            $partNum = $data_part[0]['PartNum'];
            $partDesc = $data_part[0]['PartDescription'];
            //If the price is less than or equal to zero, substitue the string "Call" for the value
            $nprice = $data_part[0]['UnitPrice'] <= 0 ? "Call" : $data_part[0]['UnitPrice'];
            $available = $qtyOnHand - $allocatedQty;
            $incoming = 'N/A';

            //Assign output
            $ret = array('citemno' => $partNum,
                'cdescript' => $partDesc,
                'nprice' => $nprice,
//              'incoming' => $incoming,
                'available' => round($available),
                'visible' => 'visible',
                'evisible' => 'hidden');
			
			//$res = json_encode($ret['result']);
			//echo $res;
			$this->set(compact('ret'));
			$this->set('_serialize', ['ret']);
        }//End If-Else
	}
	
	public function sparePartsSearch()
	{
		//$item = filter_input(INPUT_POST, "itemno");
		$debug = false;
		$item = $_POST['itemno'];
		 //Set up SOAP Objects 
        //include('vantageSoap.php');
        $params = array('CompanyID' => 'AND', 'partNum' => "$item");
        $vantageSoap = new vantageSoap('GetByID', $params);
        $vantageSoap->setup();

        //Make SOAP Call
        $result = $vantageSoap->call();
		//dedug($result);
		
        
        if (empty($result)) {
            //$ret[] = array('evisible' => 'visible',   'visible' => 'hidden');
			$ret = array('value'=>false);
			$this->set(compact('result'));
			$this->set('_serialize', ['result']);
        } else {
            $data_part = $result['GetByIDResult']['PartDataSet']['Part'];
            $data_partwhse = $result['GetByIDResult']['PartDataSet']['PartWhse'];
           
            $data_size = sizeof($data_partwhse);
            $qtyOnHand = 0;
            $allocatedQty = 0;
            for ($i = 0; $i < $data_size; $i++) {
                //Exclude Showroom Warehouses from results
                if (!strpos($data_partwhse[$i]['WarehouseCode'], 'SR')) {
                    $qtyOnHand += $data_partwhse[$i]['OnHandQty'];
                    $allocatedQty += $data_partwhse[$i]['AllocQty'];
                }//End If
            }//End For
            $partNum = $data_part[0]['PartNum'];
            $partDesc = $data_part[0]['PartDescription'];
            //If the price is less than or equal to zero, substitue the string "Call" for the value
            $nprice = $data_part[0]['UnitPrice'] <= 0 ? "Call" : $data_part[0]['UnitPrice'];
            $available = $qtyOnHand - $allocatedQty;
            $incoming = 'N/A';

            //Assign output
            $ret = array('citemno' => $partNum,
                'cdescript' => $partDesc,
                'nprice' => $nprice,
//              'incoming' => $incoming,
                'available' => round($available),
                'visible' => 'visible',
                'evisible' => 'hidden');
			
			//$res = json_encode($ret['result']);
			//echo $res;
			$this->set(compact('ret'));
			$this->set('_serialize', ['ret']);
        }//End If-Else
	}
	/* original spare parts search function
	public function sparePartsSearch()
	{
		$debug = false;
		$item = $_POST['itemno'];
		 //Set up SOAP Objects 
        //include('vantageSoap.php');
        $params=array('CompanyID' => 'AND', 'cPartNum' => $item, 'cPlant' => 'MfgSys');
        $vantageSoap = new vantageSoap1('GetPartOnHandWhse', $params);
        $vantageSoap->setup();

        //Make SOAP Call
        $result = $vantageSoap->call();
		//dedug($result);
		
        
        if (empty($result)) {
            //$ret[] = array('evisible' => 'visible',   'visible' => 'hidden');
			$ret = array('value'=>false);
			$this->set(compact('ret'));
			$this->set('_serialize', ['ret']);
        } else {
			
			$data = $result['GetPartOnHandWhseResult']['PartOnHandWhseDataSet']['PartOnHandWhse'];
      $data_size = sizeof($data);
      //echo "</br>Warehouse - Part - Qty - Allocated</br>";
      $qtyOnHand = 0;
      $allocatedQty = 0;
      for ($i=0;$i<$data_size;$i++){
        //Remove Showroom Warehouses from results
        if (!strpos($data[$i]['WarehouseCode'], 'SR')){
          //echo $data[$i]['WarehouseDesc']." - ".$data[$i]['PartNum']." - ".$data[$i]['QuantityOnHand']." - ".$data[$i]['AllocQty'].";</br>";
          $qtyOnHand += $data[$i]['QuantityOnHand'];
          $allocatedQty += $data[$i]['AllocQty'];
        }//End If
      }//End For
      $partNum = $data[0]['PartNum'];
      $partDesc = $data[0]['WarehouseDesc']; //'N/A';
      $nprice = 'N/A';
      $available = $qtyOnHand - $allocatedQty;
      $incoming = 'N/A';
      
      //Assign output
      $ret = array('citemno' => $partNum,
                              'cdescript' => $partDesc,
//                              'nprice' => round($nprice, 2),
//                              'incoming' => round($incoming),
                              'nprice' => $nprice,
                              'incoming' => $incoming,
                              'available' => round($available),
                              'visible' => 'visible',
                              'evisible' => 'hidden');
			
		$this->set(compact('ret'));
		$this->set('_serialize', ['ret']);	
			
        }//End If-Else
	}
	*/
}



class VantageSoap{

    
  public $debug = false;
  
  var $url;
  var $action;
  var $username;
  var $password;
  var $params;
  var $result_type;
  var $dataset_type;
  var $data_section;

  var $result;
  var $config;
  var $basetime;


  public function __construct($call_type, $params){
    //Read Config type and load configuration for the call_type
    $this->basetime = microtime(true);
    //$config = parse_ini_file('vantage_soap.ini',true);
    $this->params = $params;
    //$this->username = $config['config']['username'];
    $this->username = 'webuser';
    //$this->password = $config['config']['password'];
    $this->password = '3LXPT0BoV5';
    //$this->url = $config[$call_type]['url'];
    $this->url = 'http://dealers.andmercury.com.au/VantageServices/PartService.asmx?wsdl';
    //echo "URL: ".$this->url."</br>";
    //$this->action = $config[$call_type]['action'];
    $this->action = 'GetByID';
    //$this->result_type = $config[$call_type]['result_type'];
    $this->result_type = 'GetByIDResult';
    //$this->dataset_type = $config[$call_type]['dataset_type'];
    $this->dataset_type = 'PartDataSetType';
    //$this->data_section = $config[$call_type]['data_section'];
    $this->data_section = 'PartWhse';
    $constructorTime = microtime(true);
    $outputTime = $constructorTime - $this->basetime;
    if ($this->debug){
    	echo "End of Constructor - Total Script Time: $outputTime</br>";
    }//End If Debug  
  }//End __constructor

  public function setup(){
  	if ($this->debug){
  		$setupStartTime = microtime(true);
    	$setupStartOutput = $setupStartTime - $this->basetime;
    	echo "Start of Setup Function - Total Script Time: $setupStartOutput</br>";
  	}//End Debug If
    //$this->client = new WSSoapClient($this->url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));
	$this->client = new SoapClient($this->url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));
	if ($this->debug){
		$endClientSetup = microtime(true);
		$endClientOutput = $endClientSetup - $this->basetime;
		echo "End of Client Setup - Total Script Time: $endClientOutput</br>";
	}//End Debug If
	$auth="<wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'>
    <wsse:UsernameToken>
    <wsse:Username>".$this->username."</wsse:Username>
    <wsse:Password>".$this->password."</wsse:Password>
    </wsse:UsernameToken>
    </wsse:Security>";
	
	$authvalues=new SoapVar($auth,XSD_ANYXML);
	
	$headers = new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  "wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  $authvalues,
							  true);
    
    $this->client -> __setSoapHeaders($headers);	
    //Assign the Username and Password to the SOAP Client for inclusion in the Security Headers
    //$this->client->__setUsernameToken($this->username,$this->password); 
    if ($this->debug){
    	$setupEndTime = microtime(true);
    	$setupEndOutput = $setupEndTime - $this->basetime;
    	echo "End of Setup Function - Total Script Time: $setupEndOutput</br>";
    }//End Debug If
  }//End setup()
  
  public function call(){
    //Attempt to Make the SOAP Call and check for Exceptions
    try{
    if ($this->debug){
    	echo "Trying Call...</br>";
    	$callStartTime = microtime(true);
    	$callStartOutput = $callStartTime - $this->basetime;
    	echo "Start of Call Function - Total Script Time: $callStartOutput</br>";
    }//End Debug If

      $this->result = $this->client->__soapCall($this->action,array($this->params));//, array('location'=>$this->url, 'soapaction'=>$this->action));
      //echo "\nRequest:\n".$this->client->__getLastRequest()."\n";
      //echo "\nRequest:\n".$this->client->__getLastResponse()."\n";
        //echo "Request :<br>", htmlspecialchars($this->client->__getLastRequestHeaders()), "<br>";
        //echo "Response :<br>", htmlspecialchars($this->client->__getLastResponseHeaders()), "<br>";
      //If Debugging is enabled, output the SOAP Request and Response.
      if ($this->debug){
        //echo "Try Block</br>";
        //echo "Request :<br>", htmlspecialchars($this->client->__getLastRequestHeaders()), "<br>";
        //echo "Response :<br>", htmlspecialchars($this->client->__getLastResponseHeaders()), "<br>";
        //echo "------------------------------------------------</br>";
        //echo "Request:".$this->client->__getLastRequest()."</br>";
      	//echo "Request:".$this->client->__getLastResponse()."";
      }//End If
    }catch(SoapFault $fault){
      if (is_soap_fault($this->result)){
        $object_vars = get_object_vars($this->result);
        if ($this->debug){
          echo "Catch Block</br>";
          while(list($key, $value)=each($object_vars)){
            echo "$key = $value;</br>";
          }//End While
        }//ENd If Debug
      }else{
        if ($this->debug){
          echo "No further information Available!</br>";
        }
      }//End If Else
        if ($this->debug){
          echo "Catch Block</br>";
          echo "Request :<br>", htmlspecialchars($this->client->__getLastRequest()), "<br>";
          echo "Response :<br>", htmlspecialchars($this->client->__getLastResponse()), "<br>";
          echo "------------------------------------------------</br>";
          echo "Request:".$this->client->__getLastRequest()."</br>";
      	  echo "Request:".$this->client->__getLastResponse()."";  
        }//End If
        return "";
      die();
    }//End Try Catch
    if ($this->debug){
    	$callEndTime = microtime(true);
    	$callEndOutput = $callEndTime - $this->basetime;
    	echo "End of Call Function - Total Script Time: $callEndOutput</br>";
    }//End Debug If
    $this->result = $this->obj2array($this->result);
    return $this->result;
  }//End Function call()
  
  
  private function obj2array($obj) {
    //Convert the Object to a multi Dimensional Array
    //This function is freely available from the PHP Manual
    //Code provided by: stefan at datax dot biz
    //http://au.php.net/manual/en/soapclient.soapcall.php
  	if ($this->debug){
  		$obj2arrayStartTime = microtime(true);
    	$obj2arrayStartOutput = $obj2arrayStartTime - $this->basetime;
    	echo "Start of obj2array Function - Total Script Time: $obj2arrayStartOutput</br>";
  	}//End Debug If
  	$out = array();
    foreach ($obj as $key => $val) {
      switch(true) {
          case is_object($val):
           $out[$key] = $this->obj2array($val);
          break;
        case is_array($val):
           $out[$key] = $this->obj2array($val);
           break;
        default:
          $out[$key] = $val;
      }//End Switch
    }//End foreach
    if ($this->debug){
    	$obj2arrayEndTime = microtime(true);
    	$obj2arrayEndOutput = $obj2arrayEndTime - $this->basetime;
    	echo "End of obj2array Function - Total Script Time: $obj2arrayEndOutput</br>";
    }//End Debug If
  return $out;
  }//End function obj2array($obj)
	
	
	
}


class VantageSoap1{

    
  public $debug = false;
  
  var $url;
  var $action;
  var $username;
  var $password;
  var $params;
  var $result_type;
  var $dataset_type;
  var $data_section;

  var $result;
  var $config;
  var $basetime;


  public function __construct($call_type, $params){
    //Read Config type and load configuration for the call_type
    $this->basetime = microtime(true);
    //$config = parse_ini_file('vantage_soap.ini',true);
    $this->params = $params;
    //$this->username = $config['config']['username'];
    $this->username = 'webuser';
    //$this->password = $config['config']['password'];
    $this->password = '3LXPT0BoV5';
    //$this->url = $config[$call_type]['url'];
    $this->url = 'http://dealers.andmercury.com.au/VantageServices/PartOnHandWhseService.asmx?wsdl';
    //echo "URL: ".$this->url."</br>";
    //$this->action = $config[$call_type]['action'];
    $this->action = 'GetPartOnHandWhse';
    //$this->result_type = $config[$call_type]['result_type'];
    $this->result_type = 'GetPartOnHandWhseResult';
    //$this->dataset_type = $config[$call_type]['dataset_type'];
    $this->dataset_type = 'PartOnHandWhseDataSet';
    //$this->data_section = $config[$call_type]['data_section'];
    $this->data_section = 'PartOnHandWhse';
    $constructorTime = microtime(true);
    $outputTime = $constructorTime - $this->basetime;
    if ($this->debug){
    	echo "End of Constructor - Total Script Time: $outputTime</br>";
    }//End If Debug  
  }//End __constructor

  public function setup(){
  	if ($this->debug){
  		$setupStartTime = microtime(true);
    	$setupStartOutput = $setupStartTime - $this->basetime;
    	echo "Start of Setup Function - Total Script Time: $setupStartOutput</br>";
  	}//End Debug If
    //$this->client = new WSSoapClient($this->url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));
	$this->client = new SoapClient($this->url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));
	if ($this->debug){
		$endClientSetup = microtime(true);
		$endClientOutput = $endClientSetup - $this->basetime;
		echo "End of Client Setup - Total Script Time: $endClientOutput</br>";
	}//End Debug If
	$auth="<wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'>
    <wsse:UsernameToken>
    <wsse:Username>".$this->username."</wsse:Username>
    <wsse:Password>".$this->password."</wsse:Password>
    </wsse:UsernameToken>
    </wsse:Security>";
	
	$authvalues=new SoapVar($auth,XSD_ANYXML);
	
	$headers = new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  "wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  $authvalues,
							  true);
    
    $this->client -> __setSoapHeaders($headers);	
    //Assign the Username and Password to the SOAP Client for inclusion in the Security Headers
    //$this->client->__setUsernameToken($this->username,$this->password); 
    if ($this->debug){
    	$setupEndTime = microtime(true);
    	$setupEndOutput = $setupEndTime - $this->basetime;
    	echo "End of Setup Function - Total Script Time: $setupEndOutput</br>";
    }//End Debug If
  }//End setup()
  
  public function call(){
    //Attempt to Make the SOAP Call and check for Exceptions
    try{
    if ($this->debug){
    	echo "Trying Call...</br>";
    	$callStartTime = microtime(true);
    	$callStartOutput = $callStartTime - $this->basetime;
    	echo "Start of Call Function - Total Script Time: $callStartOutput</br>";
    }//End Debug If

      $this->result = $this->client->__soapCall($this->action,array($this->params));
      if ($this->debug){
      }//End If
    }catch(SoapFault $fault){
      if (is_soap_fault($this->result)){
        $object_vars = get_object_vars($this->result);
        if ($this->debug){
          echo "Catch Block</br>";
          while(list($key, $value)=each($object_vars)){
            echo "$key = $value;</br>";
          }//End While
        }//ENd If Debug
      }else{
        if ($this->debug){
          echo "No further information Available!</br>";
        }
      }//End If Else
        if ($this->debug){
          echo "Catch Block</br>";
          echo "Request :<br>", htmlspecialchars($this->client->__getLastRequest()), "<br>";
          echo "Response :<br>", htmlspecialchars($this->client->__getLastResponse()), "<br>";
          echo "------------------------------------------------</br>";
          echo "Request:".$this->client->__getLastRequest()."</br>";
      	  echo "Request:".$this->client->__getLastResponse()."";  
        }//End If
        return "";
      die();
    }//End Try Catch
    if ($this->debug){
    	$callEndTime = microtime(true);
    	$callEndOutput = $callEndTime - $this->basetime;
    	echo "End of Call Function - Total Script Time: $callEndOutput</br>";
    }//End Debug If
    $this->result = $this->obj2array($this->result);
    return $this->result;
  }//End Function call()
  
  
  private function obj2array($obj) {
    //Convert the Object to a multi Dimensional Array
    //This function is freely available from the PHP Manual
    //Code provided by: stefan at datax dot biz
    //http://au.php.net/manual/en/soapclient.soapcall.php
  	if ($this->debug){
  		$obj2arrayStartTime = microtime(true);
    	$obj2arrayStartOutput = $obj2arrayStartTime - $this->basetime;
    	echo "Start of obj2array Function - Total Script Time: $obj2arrayStartOutput</br>";
  	}//End Debug If
  	$out = array();
    foreach ($obj as $key => $val) {
      switch(true) {
          case is_object($val):
           $out[$key] = $this->obj2array($val);
          break;
        case is_array($val):
           $out[$key] = $this->obj2array($val);
           break;
        default:
          $out[$key] = $val;
      }//End Switch
    }//End foreach
    if ($this->debug){
    	$obj2arrayEndTime = microtime(true);
    	$obj2arrayEndOutput = $obj2arrayEndTime - $this->basetime;
    	echo "End of obj2array Function - Total Script Time: $obj2arrayEndOutput</br>";
    }//End Debug If
  return $out;
  }//End function obj2array($obj)
	
	
	
}
