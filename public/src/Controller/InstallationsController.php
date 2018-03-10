<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\I18n\Time;

/**
 * Installations Controller
 *
 * @property \App\Model\Table\InstallationsTable $Installations
 */
class InstallationsController extends AppController
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
		$this->set('title', 'Install Diagrams');

	}
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        
		$installation = $this->Installations->find()->where(['archived'=>'no']);
		$installations = $this->paginate($installation);
		
		
		/*$this->paginate = ([
            'contain' => ['ParentTechnicals']
        ]);
		$this->paginate['order'] = array('name' => 'asc');
		$this->paginate['conditions'] = array('Technicals.parent_id' => 0);
        $technicals = $this->paginate($this->Technicals);*/

        $this->set(compact('installations'));
        $this->set('_serialize', ['installations']);
    }

    /**
     * View method
     *
     * @param string|null $id Installation id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $installation = $this->Installations->get($id, [
            'contain' => []
        ]);

        $this->set('installation', $installation);
        $this->set('_serialize', ['installation']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $installation = $this->Installations->newEntity();
        if ($this->request->is('post')) {
			
			if($this->request->data['file_name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'installdiagrams'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
			}
			
            $installation = $this->Installations->patchEntity($installation, $this->request->data);
            if ($this->Installations->save($installation)) {
                $this->Flash->success(__('The installation has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The installation could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('installation'));
        $this->set('_serialize', ['installation']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Installation id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
       $oldInstallation = $this->Installations->find()
			->where(['id'=>$id])->toArray();
		
		$installation = $this->Installations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			if($this->request->data['file_name']['name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'installdiagrams'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
				
				unlink(WWW_ROOT.DS.'uploads'.DS.'installdiagrams'.DS.$oldInstallation[0]->file_name);
			}
			else
			{
				unset($this->request->data['file_name']);
			}
			
            $installation = $this->Installations->patchEntity($installation, $this->request->data);
            if ($this->Installations->save($installation)) {
                $this->Flash->success(__('The installation has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The installation could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('installation'));
        $this->set('_serialize', ['installation']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Installation id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        /*$this->request->allowMethod(['post', 'delete']);
        $installation = $this->Installations->get($id);
        if ($this->Installations->delete($installation)) {
            $this->Flash->success(__('The installation has been deleted.'));
        } else {
            $this->Flash->error(__('The installation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);*/
		
		
		$id=$this->request->pass[0];
		$installation = $this->Installations->get($id);
		$this->request->data['archived'] = 'yes';
		$this->request->data['archived_date'] =  new Time();
		
        /*if ($this->request->is(['patch', 'post', 'put'])) {*/
            $installation = $this->Installations->patchEntity($installation, $this->request->data);
            if ($this->Installations->save($installation)) {
                $this->Flash->success(__('The installation has been deleted.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The installation could not be deleted. Please, try again.'));
            }
        //}
		return $this->redirect(['action' => 'index']);
		
    }
}
