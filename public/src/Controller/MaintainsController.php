<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\I18n\Time;


/**
 * Maintains Controller
 *
 * @property \App\Model\Table\MaintainsTable $Maintains
 */
class MaintainsController extends AppController
{

    public function initialize()
    {
	
        parent::initialize();
        /*$this->viewBuilder()->layout('dashboard');
		$this->viewBuilder()->layout('dashboard');
		$this->loadModel('Roles');
		$this->loadModel('Permissions');
        $this->loadModel('Currencies');
		$this->loadModel('UserPermissions');
        $this->loadModel('UserSettings');

        $this->loadComponent('RBruteForce');*/
		$this->set('title', 'Maintenance');

	}
	
	/**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentMaintains']
        ];
		$this->paginate['order'] = array('weight' => 'DESC');
		$this->paginate['conditions'] = array('Maintains.parent_id' => 0);
        $maintains = $this->paginate($this->Maintains);

        $this->set(compact('maintains'));
        $this->set('_serialize', ['maintains']);
    }

    /**
     * View method
     *
     * @param string|null $id Maintain id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $maintain = $this->Maintains->get($id, [
            'contain' => ['ParentMaintains', 'ChildMaintains']
        ]);

        $this->set('maintain', $maintain);
        $this->set('_serialize', ['maintain']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $maintain = $this->Maintains->newEntity();
        if ($this->request->is('post')) {
			if($this->request->data['file_name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'maintenancemanuals'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
			}
            $maintain = $this->Maintains->patchEntity($maintain, $this->request->data);
            if ($this->Maintains->save($maintain)) {
                $this->Flash->success(__('The maintain has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The maintain could not be saved. Please, try again.'));
            }
        }
        //$parentMaintains = $this->Maintains->ParentMaintains->find('list', ['limit' => 200]);
        $parentMaintains = $this->Maintains->ParentMaintains->find('list')->where(['parent_id'=>0]);
        $this->set(compact('maintain', 'parentMaintains'));
        $this->set('_serialize', ['maintain']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Maintain id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
       	$oldMaintain = $this->Technicals->find()
			->where(['id'=>$id])->toArray();
		
		//$parentMaintains = $this->Technicals->find()->where(['id'=>$oldMaintain[0]->parent_id])->toArray();
		
		
		$maintain = $this->Maintains->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			if($this->request->data['file_name']['name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'maintenancemanuals'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
				
				unlink(WWW_ROOT.DS.'uploads'.DS.'maintenancemanuals'.DS.$oldMaintain[0]->file_name);
			}
			else
			{
				unset($this->request->data['file_name']);
			}
			
            $maintain = $this->Maintains->patchEntity($maintain, $this->request->data);
            if ($this->Maintains->save($maintain)) {
                $this->Flash->success(__('The maintain has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The maintain could not be saved. Please, try again.'));
            }
        }
        //$parentMaintains = $this->Maintains->ParentMaintains->find('list', ['limit' => 200]);
        $parentMaintains = $this->Maintains->ParentMaintains->find('list')->where(['parent_id'=>0]);
        $this->set(compact('maintain', 'parentMaintains'));
        $this->set('_serialize', ['maintain']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Maintain id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id=$this->request->pass[0];
		$maintain = $this->Maintains->get($id);
		$this->request->data['archived'] = 'yes';
		$this->request->data['archived_date'] =  new Time();
		
            $maintain = $this->Maintains->patchEntity($maintain, $this->request->data);
            if ($this->Maintains->save($maintain)) {
                $this->Flash->success(__('The maintain has been deleted.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The maintain could not be deleted. Please, try again.'));
            }
		return $this->redirect(['action' => 'index']);
		
		/*$this->request->allowMethod(['post', 'delete']);
        $maintain = $this->Maintains->get($id);
        if ($this->Maintains->delete($maintain)) {
            $this->Flash->success(__('The maintain has been deleted.'));
        } else {
            $this->Flash->error(__('The maintain could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);*/
    }
}
