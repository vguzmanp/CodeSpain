<?php
/* @var $this DetalleEventoController */
?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span10">

			<div class="span7">
				<?php echo "<img src='$ModeloEvento->Imagen'/>" ?>
			</div>

			<div class="span5">
				<h4> Datos evento </h4>
				<b>Nombre del Evento:</b>
				<p><?php echo $ModeloEvento->Nombre ?></p>
				<b>Localizaci&oacute;n:</b>
				<p><?php echo $ModeloEvento->Lugar ?></p>
				<b>Fecha de inicio:</b>
				<p><?php echo date("d-m-Y",strtotime($ModeloEvento->FechaIni)); ?></p>
				<b>Fecha de fin:</b>
				<p><?php echo date("d-m-Y",strtotime($ModeloEvento->FechaFin)); ?></p>
				<b>Página Web</b>
				<p><a target="blank" href="<?php echo 'http://'.$ModeloEvento->Web; ?>"><?php echo $ModeloEvento->Web; ?></a></p>
			</div>
		</div>

		<div class="span10">
				<h4> Descripci&oacute;n del evento </h4>
				<?php echo "<p>".$ModeloEvento->Descripcion."</p>" ?>
		</div>

		<div class="span10">

			<?php  $this->widget('bootstrap.widgets.TbButton', array(
			    'label'=>'Ver en mapa',
			    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
			    'size'=>'small', // null, 'large', 'small' or 'mini'
			    'url'=>'#',
			    'htmlOptions'=>array('data-dismiss'=>'modal', 'onclick' => "CentrarEnCoodenadas($ModeloEvento->idEventos)"),
			)); ?>

		</div>

	</div>
</div>
