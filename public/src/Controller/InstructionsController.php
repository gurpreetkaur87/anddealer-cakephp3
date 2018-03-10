<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\I18n\Time;

/**
 * Instructions Controller
 *
 * @property \App\Model\Table\InstructionsTable $Instructions
 */
class InstructionsController extends AppController
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
		$this->set('title', 'Instruction Manuals');

	}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentInstructions']
        ];
		$this->paginate['order'] = array('weight' => 'DESC');
		$this->paginate['conditions'] = array('Instructions.parent_id' => 0);
		
        $instructions = $this->paginate($this->Instructions);

        $this->set(compact('instructions'));
        $this->set('_serialize', ['instructions']);
    }

    /**
     * View method
     *
     * @param string|null $id Instruction id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $instruction = $this->Instructions->get($id, [
            'contain' => ['ParentInstructions', 'ChildInstructions']
        ]);

        $this->set('instruction', $instruction);
        $this->set('_serialize', ['instruction']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $instruction = $this->Instructions->newEntity();
        if ($this->request->is('post')) {
			if($this->request->data['file_name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'instructionmanuals'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
			}
            $instruction = $this->Instructions->patchEntity($instruction, $this->request->data);
            if ($this->Instructions->save($instruction)) {
                $this->Flash->success(__('The instruction has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The instruction could not be saved. Please, try again.'));
            }
        }
        //$parentInstructions = $this->Instructions->ParentInstructions->find('list', ['limit' => 200]);
		$parentInstructions = $this->Instructions->find('list')->where(['parent_id' => 0]);
        $this->set(compact('instruction', 'parentInstructions'));
        $this->set('_serialize', ['instruction']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Instruction id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $oldInstructions = $this->Technicals->find()
			->where(['id'=>$id])->toArray();
		
		$instruction = $this->Instructions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			if($this->request->data['file_name']['name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'instructionmanuals'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
				
				unlink(WWW_ROOT.DS.'uploads'.DS.'instructionmanuals'.DS.$oldInstructions[0]->file_name);
			}
			else
			{
				unset($this->request->data['file_name']);
			}
			
            $instruction = $this->Instructions->patchEntity($instruction, $this->request->data);
            if ($this->Instructions->save($instruction)) {
                $this->Flash->success(__('The instruction has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The instruction could not be saved. Please, try again.'));
            }
        }
        //$parentInstructions = $this->Instructions->ParentInstructions->find('list', ['limit' => 200]);
        $parentInstructions = $this->Instructions->ParentInstructions->find('list')->where(['parent_id' => 0]);
        $this->set(compact('instruction', 'parentInstructions'));
        $this->set('_serialize', ['instruction']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Instruction id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id=$this->request->pass[0];
		$instruction = $this->Instructions->get($id);
		$this->request->data['archived'] = 'yes';
		$this->request->data['archived_date'] =  new Time();
		
            $instruction = $this->Instructions->patchEntity($instruction, $this->request->data);
            if ($this->Instructions->save($instruction)) {
                $this->Flash->success(__('The instruction has been deleted.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The instruction could not be deleted. Please, try again.'));
            }
		return $this->redirect(['action' => 'index']);
		
		
		/*$this->request->allowMethod(['post', 'delete']);
        $instruction = $this->Instructions->get($id);
        if ($this->Instructions->delete($instruction)) {
            $this->Flash->success(__('The instruction has been deleted.'));
        } else {
            $this->Flash->error(__('The instruction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);*/
    }
}
