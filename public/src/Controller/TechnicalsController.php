<?php
namespace App\Controller;
//use Cake\View\Helper;
use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\I18n\Time;

/**
 * Technicals Controller
 *
 * @property \App\Model\Table\TechnicalsTable $Technicals
 */
class TechnicalsController extends AppController
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
		$this->set('title', 'Technicals');

	}

	/**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        
		$this->paginate = ([
            'contain' => ['ParentTechnicals']
        ]);
		$this->paginate['order'] = array('name' => 'asc');
		$this->paginate['conditions'] = array('Technicals.parent_id' => 0);
        $technicals = $this->paginate($this->Technicals);

        $this->set(compact('technicals'));
        $this->set('_serialize', ['technicals']);
    }

    /**
     * View method
     *
     * @param string|null $id Technical id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $technical = $this->Technicals->get($id, [
            'contain' => ['ParentTechnicals', 'ChildTechnicals']
        ]);
		/*$technical = $this->Technicals->get($id, [
            'contain' => ['ParentTechnicals', 'ChildTechnicals'], 'where'=>['archived'=>'no']
        ]);
		$technical = $this->Technicals->find()
   ->where(['ParentTechnicals.parent_id' => $id,'ChildTechnicals.archived'=>'no'])
   ->contain(['ParentTechnicals', 'ChildTechnicals'])->toArray();*/
		
        $this->set('technical', $technical);
        $this->set('_serialize', ['technical']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {	
		$technical = $this->Technicals->newEntity();
        if ($this->request->is('post')) {
			$parentTechnicals = $this->Technicals->find()
			->where(['id'=>$this->request->data['parent_id']])->toArray();
			if($this->request->data['file_name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'techdocs'.DS.$parentTechnicals[0]->folder_name.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
			}
			$technical = $this->Technicals->patchEntity($technical, $this->request->data);
			if ($this->Technicals->save($technical)) {
				$this->Flash->success(__('The technical has been saved.'));

				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The technical could not be saved. Please, try again.'));
			}
        }
        //$parentTechnicals = $this->Technicals->ParentTechnicals->find('list', ['limit' => 200]);
        $parentTechnicals = $this->Technicals->ParentTechnicals->find('list')->where(['parent_id' => 0]);
        $this->set(compact('technical', 'parentTechnicals'));
        $this->set('_serialize', ['technical']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Technical id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        
		
		$oldTechnical = $this->Technicals->find()
			->where(['id'=>$id])->toArray();
		
		$parentTechnicals = $this->Technicals->find()
			->where(['id'=>$oldTechnical[0]->parent_id])->toArray();
		
		$technical = $this->Technicals->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			//debug($this->request->data); die();
			if($this->request->data['file_name']['name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'techdocs'.DS.$parentTechnicals[0]->folder_name.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
				
				unlink(WWW_ROOT.DS.'uploads'.DS.'techdocs'.DS.$parentTechnicals[0]->folder_name.DS.$oldTechnical[0]->file_name);
			}
			else
			{
				unset($this->request->data['file_name']);
			}
            $technical = $this->Technicals->patchEntity($technical, $this->request->data);
            if ($this->Technicals->save($technical)) {
                $this->Flash->success(__('The technical has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technical could not be saved. Please, try again.'));
            }
        }
        //$parentTechnicals = $this->Technicals->ParentTechnicals->find('list', ['limit' => 200]);
        $parentTechnicals = $this->Technicals->ParentTechnicals->find('list')->where(['parent_id' => 0]);
        $this->set(compact('technical', 'parentTechnicals'));
        $this->set('_serialize', ['technical']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Technical id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
		$id=$this->request->pass[0];
		$technical = $this->Technicals->get($id);
		$this->request->data['archived'] = 'yes';
		$this->request->data['archived_date'] =  new Time();
		
        /*if ($this->request->is(['patch', 'post', 'put'])) {*/
            $technical = $this->Technicals->patchEntity($technical, $this->request->data);
            if ($this->Technicals->save($technical)) {
                $this->Flash->success(__('The technical has been deleted.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The technical could not be deleted. Please, try again.'));
            }
        //}
		return $this->redirect(['action' => 'index']);
		
		
		
		/*$this->request->allowMethod(['post', 'delete']);
        $technical = $this->Technicals->get($id);
        if ($this->Technicals->delete($technical)) {
            $this->Flash->success(__('The technical has been deleted.'));
        } else {
            $this->Flash->error(__('The technical could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);*/
    }
}
