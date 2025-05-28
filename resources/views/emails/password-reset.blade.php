<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperación de Contraseña</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

  <div style="max-width: 600px; margin: 40px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    
    <!-- Encabezado con fondo degradado -->
    <div style="background: linear-gradient(90deg, #007bff, #00c6ff); padding: 20px 0; text-align: center;">
      <h2 style="color: white; margin: 0;">Recuperación de Contraseña</h2>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <img src="https://i.postimg.cc/hGVx9nbH/HolaEpc.png" alt="Logo" style="width: 150px;">
    </div>

    <!-- Cuerpo del mensaje -->
    <div style="padding: 30px; color: #333;">
      <p style="font-size: 16px;">Hola {{ $nombre }},</p>
      <p style="font-size: 16px;">
        Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente botón para continuar:
      </p>

      <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 6px;">Restablecer Contraseña</a>
      </div>

      <p style="font-size: 14px; color: #666;">
        Este enlace expirará en 60 minutos.
      </p>

      <p style="font-size: 14px; color: #666;">
        Si no solicitaste este cambio, puedes ignorar este mensaje.
      </p>
    </div>

    <!-- Pie de página -->
    <div style="background-color: #f0f0f0; padding: 20px; text-align: center; font-size: 12px; color: #999;">
      © 2025 Sistema de Gestión Vehicular - Cajicá. Todos los derechos reservados.
    </div>
  </div>

</body>
</html>
