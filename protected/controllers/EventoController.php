<?php

class EventoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
				),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
				),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
				),
			array('deny',  // deny all users
				'users'=>array('*'),
				),
			);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			));
	}
	
	function extensionCorrecta($exten){
		return ($exten==='jpg' or $exten==='jpeg' or $exten==='png' or $exten==='gif' or $exten==='bmp' or $exten==='jpg2');
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl . '/js/validarNuevoEvento.js',
			CClientScript::POS_END
			);

		$model=new Eventos();
		$controladorTag = new TagController('Tag');
		$modeloRelacion = new EventosHasTag();
		$etiquetas = array();
		$valores = array('Nombre'=>'','Descripcion'=>'','Lugar'=>'','CoordX'=>'','CoordY'=>'','FechaFin'=>'','FechaIni'=>'','Imagen'=>'','tags'=>'','Web'=>'','idUsuarioCrear'=>'');

		$tags = $controladorTag->GetAll();
		for ($i=0; $i < count($tags); $i++) { 
			$nuevoElemento = array(
				'id'=> $i,
				'text'=> $tags[$i]->Etiqueta,
				);
			$etiquetas[$i] = $nuevoElemento;
		}
		$valores['idUsuarioCrear'] = Yii::app()->user->getId();
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		if(isset($_POST['Eventos']))
		{
			$tagsOK = false;
			$model->attributes=$_POST['Eventos'];
			$uploadedFile=CUploadedFile::getInstance($model,'Imagen');
			$rnd = md5($uploadedFile.date("d-m-Y H:i:s"));
            $fileName = "{$rnd}-{$uploadedFile}";  // random number + file name
            $model->Imagen = 'images/Eventos/'.$fileName;
            $array_tags = explode(',',$_POST['Eventos_tags']);
            $tags_strings = array();
            
            foreach ($array_tags as $value) {
	        	if(is_numeric($value))
	        	{
	        		$tagsOK = true;
	        	 }else{
	        		echo "OUCH";
	        	}
	        }
            //$model->setRelationRecords('tags',$tags_strings);
            $ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);
            if($model->validate()){

            	if($tagsOK == true){

            		if($this->extensionCorrecta(strtolower($ext)))
            		{
            			$model->scenario = 'registerwcaptcha';
						if($model->validate(array('validacion'))) { // will validate only one attribute
							$model->scenario = NULL;
							if($model->save()){
								$modeloRelacion->Eventos_idEventos = $model->idEventos;
								foreach ($array_tags as $value) {
									$modeloRelacion->setIsNewRecord(true);
									$modeloRelacion->Tag_Etiqueta = $etiquetas[$value]['text'];
									$modeloRelacion->insert();
								}

								$images_path = realpath(Yii::app()->basePath . '/../images/Eventos');
								$uploadedFile->saveAs($images_path.'/'.$fileName);
								$expire_date_correct = '¡Evento creado!';
								Yii::app()->user->setFlash('expire_date_correct',$expire_date_correct);
							}
						}
						else{
							$this->restaurarDatos($uploadedFile);
							$expire_date_error = 'Has escrito el recaptcha mal. ¡Intentalo de nuevo!';
							Yii::app()->user->setFlash('expire_date_error',$expire_date_error);
						}
					}
					else
					{
						$this->restaurarDatos($uploadedFile);
						$expire_date_error = '¡Formato de imagen incorrecto!';
						Yii::app()->user->setFlash('expire_date_error',$expire_date_error);
					}
				}else{
					$this->restaurarDatos($uploadedFile);
					$expire_date_error = '¡Etiqueta el evento!';
					Yii::app()->user->setFlash('expire_date_error',$expire_date_error);
				}
			}
			else
			{
				$error = CActiveForm::validate($model);
				$this->restaurarDatos($uploadedFile);

                /*if($error!='[]')
                {
                    $expire_date_error = $error;
                    Yii::app()->user->setFlash('expire_date_error',$expire_date_error);
                }*/
            }
        }


        $this->render('create',array(
        	'model'=>$model,
        	'etiquetas'=>$etiquetas,
        	'valores'=>$valores,
        	));
    }
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Eventos']))
		{
			$model->attributes=$_POST['Eventos'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->idEventos));
		}
		

		$this->render('update',array(
			'model'=>$model,
			));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Eventos');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			));
	}

	public function GetAll()
	{
		$dataProvider = new CActiveDataProvider('Eventos');
		$array_eventos = $dataProvider->getData();
		return $array_eventos;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Eventos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Eventos']))
			$model->attributes=$_GET['Eventos'];

		$this->render('admin',array(
			'model'=>$model,
			));
	}

	public function actionSearch($keyword)
	{

		$model=Eventos::model()->findAllByAttributes(
			array(),
			$condition  = 'Nombre = :keyword OR Descripcion = :keyword',
			$params     = array(
				':keyword' => $keyword,
				)
			);

		$dato = $model;
		return $dato;
	}
	public function actionAjax()
	{
		$this->renderPartial('ajaxView', array(), false, true);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Eventos::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='eventos-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	private function restaurarDatos($uploadedFile){
		$valores['Nombre']=$_POST['Eventos']['Nombre'];
		$valores['Descripcion'] = $_POST['Eventos']['Descripcion'];
		$valores['Lugar'] = $_POST['Eventos']['Lugar'];
		$valores['FechaIni'] = $_POST['Eventos']['FechaIni'];
		$valores['FechaFin'] = $_POST['Eventos']['FechaFin'];
		$valores['CoordX'] = $_POST['Eventos']['CoordX'];
		$valores['CoordY'] = $_POST['Eventos']['CoordY'];
		$valores['Imagen'] = $uploadedFile;
		$valores['Web'] = $_POST['Eventos']['Web'];
		$valores['idUsuarioCrear'] = Yii::app()->user->getId();
	}

	

}