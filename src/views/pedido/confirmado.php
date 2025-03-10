

<?php if (isset($_SESSION['pedido']) && $_SESSION['pedido'] == 'completed') : ?>
<h1>Tu pedido se ha realizado correctamente</h1>
<p>Tu pedido ha sido guardado con exito. Una vez que realices la transferencia bancaria a la cuenta ESXX XXXX XXXX XXXX XXXX XXXX con el coste del pedido será procesado y enviado.</p>


<br>
<?php if (isset($pedido)) : ?>
<h3>Datos del pedido:</h3>
<br>
Numero de pedido: <?= $pedido->id ?><br>
Total a pagar: <?= $pedido->coste ?> €<br>
Productos:
<table>
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Unidades</th>
    </tr>
    <?php while ($producto = $productos->fetch_object()) : ?>


    <tr>
        <td><?php if ($producto->imagen != null) : ?>
            <img src="<?= base_url ?>uploads/images/<?= $producto->imagen ?>" alt="<?= $producto->nombre ?>" class="img-carrito">
            <?php else : ?>
            <img src="<?= base_url ?>uploads/images/mesa_comedor-2640.jpg" alt="mesa de comer" class="img-carrito">
            <?php endif; ?>
        </td>
        <td><a href="<?= base_url ?>producto/ver&id=<?= $producto->id ?>"><?= $producto->nombre ?></a></td>
        <td><?= $producto->precio ?></td>
        <td><?= $producto->unidades ?></td>
    </tr>


    <?php endwhile; ?>
</table>
<br>

<?php endif; ?>
<?php 
require_once './helpers/mailHelpers.php';
    // Enviar correo de confirmación
    if (isset($_SESSION['identity'])) {
        $emailCliente = $_SESSION['identity']->email;
        $correoEnviado = enviarCorreoConfirmacion($emailCliente, $pedido, $productos);

        if ($correoEnviado) {
            echo "<p>Se ha enviado un correo de confirmación a tu email: $emailCliente</p>";
        } else {
            echo "<p>Hubo un problema enviando el correo de confirmación.</p>";
        }
    }
    ?>

<?php Utils::deleteSession('carrito'); ?>
<?php elseif (isset($_SESSION['pedido']) && $_SESSION['pedido'] == 'failed') : ?>
<h1>Tu pedido no ha podido realizarse</h1>
<?php endif; ?>