<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;
use Cake\I18n\Time;

/**
 * ProductDiagrams Controller
 *
 * @property \App\Model\Table\ProductDiagramsTable $ProductDiagrams
 */
class ProductDiagramsController extends AppController
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
		$this->set('title', 'Product Diagrams');

	}
	
	/**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentProductDiagrams']
        ];
		$this->paginate['order'] = array('weight' => 'asc');
		$this->paginate['conditions'] = array('ProductDiagrams.parent_id' => 0);
		
        $productDiagrams = $this->paginate($this->ProductDiagrams);

        $this->set(compact('productDiagrams'));
        $this->set('_serialize', ['productDiagrams']);
    }

    /**
     * View method
     *
     * @param string|null $id Product Diagram id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productDiagram = $this->ProductDiagrams->get($id, [
            'contain' => ['ParentProductDiagrams', 'ChildProductDiagrams']
        ]);

        $this->set('productDiagram', $productDiagram);
        $this->set('_serialize', ['productDiagram']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productDiagram = $this->ProductDiagrams->newEntity();
        if ($this->request->is('post')) {
			if($this->request->data['file_name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'productdiagrams'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
			}
            $productDiagram = $this->ProductDiagrams->patchEntity($productDiagram, $this->request->data);
            if ($this->ProductDiagrams->save($productDiagram)) {
                $this->Flash->success(__('The product diagram has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The product diagram could not be saved. Please, try again.'));
            }
        }
        //$parentProductDiagrams = $this->ProductDiagrams->ParentProductDiagrams->find('list', ['limit' => 200]);
        $parentProductDiagrams = $this->ProductDiagrams->ParentProductDiagrams->find('list')->where(['parent_id'=>0]);
        $this->set(compact('productDiagram', 'parentProductDiagrams'));
        $this->set('_serialize', ['productDiagram']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Diagram id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
       $oldProductDiagrams = $this->Technicals->find()
			->where(['id'=>$id])->toArray();
		
		$productDiagram = $this->ProductDiagrams->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			if($this->request->data['file_name']['name'] != '')
			{
				$pdfName = date("YmdHis").$this->request->data['file_name']['name'];
				//$target_dir = "../img/ivf-lab/";
				$filename = WWW_ROOT. DS . 'uploads'.DS.'productdiagrams'.DS.$pdfName;
				//$target_file = $target_dir . $pictureName;
				$uploaded = move_uploaded_file($this->request->data['file_name']["tmp_name"], $filename);

				unset($this->request->data['file_name']);
				$this->request->data['file_name'] = $pdfName;
				
				unlink(WWW_ROOT.DS.'uploads'.DS.'productdiagrams'.DS.$oldProductDiagrams[0]->file_name);
			}
			else
			{
				unset($this->request->data['file_name']);
			}
			
            $productDiagram = $this->ProductDiagrams->patchEntity($productDiagram, $this->request->data);
            if ($this->ProductDiagrams->save($productDiagram)) {
                $this->Flash->success(__('The product diagram has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The product diagram could not be saved. Please, try again.'));
            }
        }
        //$parentProductDiagrams = $this->ProductDiagrams->ParentProductDiagrams->find('list', ['limit' => 200]);
		$parentProductDiagrams = $this->ProductDiagrams->ParentProductDiagrams->find('list')->where(['parent_id'=>0]);
        $this->set(compact('productDiagram', 'parentProductDiagrams'));
        $this->set('_serialize', ['productDiagram']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Diagram id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        
		$id=$this->request->pass[0];
		$productDiagram = $this->ProductDiagrams->get($id);
		$this->request->data['archived'] = 'yes';
		$this->request->data['archived_date'] =  new Time();
		
            $productDiagram = $this->ProductDiagrams->patchEntity($productDiagram, $this->request->data);
            if ($this->ProductDiagrams->save($productDiagram)) {
                $this->Flash->success(__('The product diagram has been deleted.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The product diagram could not be deleted. Please, try again.'));
            }
		return $this->redirect(['action' => 'index']);
		
		/*$this->request->allowMethod(['post', 'delete']);
        $productDiagram = $this->ProductDiagrams->get($id);
        if ($this->ProductDiagrams->delete($productDiagram)) {
            $this->Flash->success(__('The product diagram has been deleted.'));
        } else {
            $this->Flash->error(__('The product diagram could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);*/
    }
}
