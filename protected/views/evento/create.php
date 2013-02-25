<?php
/* @var $this EventosController */

$this->breadcrumbs=array(
	'Evento'=>array('/evento'),
	'Nuevo',
);
?>
<?php /** @var BootActiveForm $form */
$model = new Eventos();
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'nuevoEvento',
    'type'=>'horizontal',
    'method'=>'post',
)); ?>
 
<fieldset>
 
    <legend>Nuevo Evento</legend>
 	<!-- Necesito Nombre, Descripcion, Lugar, Fecha, CoordX, Coordy, tags-->


    <?php echo $form->textFieldRow($model, 'Nombre'); ?>
    <?php echo $form->textAreaRow($model, 'Descripcion', array('class'=>'span8', 'rows'=>5, )); ?>
    <div class="control-group">
    	<label class="control-label">Lugar</label>
    	<div class="controls">
	    	<?php $this->widget('bootstrap.widgets.TbTypeahead', array(
			    'name'=>'WLugar',
			    'options'=>array(
			        'source'=>array('Madrid', 'Barcelona', 'Valencia', 'Alicante', 'Granada', 'Bilbao', 'San Sebastián', 'A Coruña', 'Santiago de Compostela', 'Sevilla'),
			        'items'=>4,
			        'matcher'=>"js:function(item) {
			            return ~item.toLowerCase().indexOf(this.query.toLowerCase());
			        }",
			    ),
			)); ?>
		</div>
    </div>
    <?php echo $form->textFieldRow($model, 'Fecha'); ?>
    <div class="control-group">
    	<label class="control-label">Etiquetas</label>
    	<div class="controls">
			<?php
			//$tags=array(array('id'=>'1','text'=>'Satu'),array('id'=>'2','text'=>'Dua'),array('id'=>'3','text'=>'Tiga'));

			echo CHtml::textField('test','',array('id'=>'test'));
			$this->widget('ext.select2.ESelect2',array(
			  'selector'=>'#test',
			  'options'=>array(
			    'data'=>$data,
			    'width'=>'200px',
			    'multiple'=>'true'
			  ),
			));
			?>
			</div>
    </div>
   	
     <?php /*echo CHtml::activeLabel($model, 'validacion'); ?>
		<?php $this->widget('application.extensions.recaptcha.EReCaptcha', 
		   array('model'=>$model, 'attribute'=>'validacion',
		         'theme'=>'red', 'language'=>'es_ES', 
		         'publicKey'=>'6LemVd0SAAAAALWoJdj_2skhhO22FBpXxPyczwS1')) ?>
		<?php echo CHtml::error($model, 'validacion'); */?> 
</fieldset>
 
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Reset')); ?>
</div>
 
<?php $this->endWidget(); ?>
