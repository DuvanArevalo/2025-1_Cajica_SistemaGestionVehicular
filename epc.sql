-- Usuarios
CREATE TABLE `usuarios` (
  `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre1` varchar(50) NOT NULL,
  `nombre2` varchar(50) DEFAULT NULL,
  `apellido1` varchar(50) NOT NULL,
  `apellido2` varchar(50) DEFAULT NULL,
  `correo` varchar(255) NOT NULL,
  `correo_verificado_en` timestamp NULL DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `id_tipo_documento` int(10) UNSIGNED NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `id_rol` int(10) UNSIGNED NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  INDEX (`id_tipo_documento`),
  INDEX (`id_rol`),
  UNIQUE KEY `correo_unico` (`correo`),
  UNIQUE KEY `documento_unico` (`id_tipo_documento`, `numero_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipos_documento` ( -- cédula, pasaporte, extranjería, etc.
  `id_tipo_documento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(255) NOT NULL,
  `abreviatura` varchar(10) NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tipo_documento`),
  UNIQUE KEY `tipo_documento_unico` (`tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` ( -- Admin, SST, Conductor
  `id_rol` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(255) NOT NULL,
  `descripcion_rol` text DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `nombre_rol_unico` (`nombre_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `rol_funcionalidad` (
  `id_rol_funcionalidad` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_rol` int(10) UNSIGNED NOT NULL,
  `id_funcionalidad` int(10) UNSIGNED NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rol_funcionalidad`),
  INDEX (`id_rol`),
  INDEX (`id_funcionalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `funcionalidades` ( -- Módulo de usuario (por tipo), vehículo, etc.
  `id_funcionalidad` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_funcionalidad` varchar(255) NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_funcionalidad`),
  UNIQUE KEY `funcionalidad_unica` (`nombre_funcionalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vehículos
CREATE TABLE `vehiculos` (
  `id_vehiculo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_tipo_vehiculo` int(10) UNSIGNED NOT NULL,
  `id_marca` int(10) UNSIGNED NOT NULL,
  `id_modelo` int(10) UNSIGNED NOT NULL,
  `anio_modelo` varchar(4) NOT NULL,
  `numero_ruedas` varchar(2) NOT NULL,
  `color` varchar(50) NOT NULL,
  `placa` varchar(6) NOT NULL,
  `kilometraje` int(10) DEFAULT 0,
  `estado_vehiculo` tinyint(1) DEFAULT 1,
  `soat` timestamp NULL DEFAULT NULL,
  `estado_soat` tinyint(1) DEFAULT 1,
  `tecnomecanica` timestamp NULL DEFAULT NULL,
  `estado_tecnomecanica` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_vehiculo`),
  INDEX (`id_tipo_vehiculo`),
  INDEX (`id_marca`),
  INDEX (`id_modelo`),
  UNIQUE KEY `placa_unica` (`placa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipos_vehiculo` ( -- Camioneta, sedán, moto, camión, etc.
  `id_tipo_vehiculo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(150) NOT NULL,
  `descripcion_tipo` varchar(255) NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tipo_vehiculo`),
  UNIQUE KEY `tipo_unico` (`nombre_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `marcas` ( -- Chevrolet, Ford, Volkswagen, Deere, Caterpillar, etc.
  `id_marca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_marca` varchar(150) NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_marca`),
  UNIQUE KEY `marca_unica` (`nombre_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `modelos` ( -- Spark, Ranger, Jetta, etc.
  `id_modelo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_modelo` varchar(150) NOT NULL,
  `id_marca` int(10) UNSIGNED NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_modelo`),
  INDEX (`id_marca`),
  UNIQUE KEY `modelo_unico` (`nombre_modelo`, `id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Formularios preoperacionales
CREATE TABLE `alertas` ( -- Si: respuesta == 0 || observación != null
  `id_alerta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_formulario` int(10) UNSIGNED NOT NULL,
  `id_respuesta` int(10) UNSIGNED NOT NULL,
  `id_observacion` int(10) UNSIGNED NULL,
  `id_estado_alerta` int(10) UNSIGNED NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_alerta`),
  INDEX (`id_formulario`),
  INDEX (`id_respuesta`),
  INDEX (`id_observacion`),
  INDEX (`id_estado_alerta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `estados_alerta` ( -- Notificada, bajo revisión, cerrada.
  `id_estado_alerta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_estado` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_estado_alerta`),
  UNIQUE KEY `estado_unico` (`tipo_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `formularios_preoperacionales` (
  `id_formulario_preoperacional` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_vehiculo` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nuevo_kilometraje` int(10) UNSIGNED NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_formulario_preoperacional`),
  INDEX (`id_vehiculo`),
  INDEX (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `secciones` (
  `id_seccion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_seccion` varchar(150) NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_seccion`),
  UNIQUE KEY `seccion_unica` (`nombre_seccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seccion_tipo_vehiculo` (
  `id_seccion_tipo_vehiculo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_seccion` int(10) UNSIGNED NOT NULL,
  `id_tipo_vehiculo` int(10) UNSIGNED NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_seccion_tipo_vehiculo`),
  INDEX (`id_seccion`),
  INDEX (`id_tipo_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `preguntas` (
  `id_pregunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_seccion` int(10) UNSIGNED NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pregunta`),
  UNIQUE KEY `pregunta_unica` (`pregunta`),
  INDEX (`id_seccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `respuestas` (
  `id_respuesta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_formulario` int(10) UNSIGNED NOT NULL,
  `id_pregunta` int(10) UNSIGNED NOT NULL,
  `respuesta` tinyint(1) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_respuesta`),
  INDEX (`id_formulario`),
  INDEX (`id_pregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `observaciones` (
  `id_observacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_formulario` int(10) UNSIGNED NOT NULL,
  `id_seccion` int(10) UNSIGNED NOT NULL,
  `observacion` text DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_observacion`),
  INDEX (`id_formulario`),
  INDEX (`id_seccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- RELACIONES
-- Para la tabla usuarios:
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_ref_rol` 
    FOREIGN KEY (`id_rol`) 
    REFERENCES `roles`(`id_rol`),
  ADD CONSTRAINT `fk_usuario_ref_tipo_documento` 
    FOREIGN KEY (`id_tipo_documento`) 
    REFERENCES `tipos_documento`(`id_tipo_documento`);

-- Para la tabla vehiculos:
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculos_ref_tipo_vehiculo` 
    FOREIGN KEY (`id_tipo_vehiculo`) 
    REFERENCES `tipos_vehiculo`(`id_tipo_vehiculo`),
  ADD CONSTRAINT `fk_vehiculos_ref_marca` 
    FOREIGN KEY (`id_marca`) 
    REFERENCES `marcas`(`id_marca`),
  ADD CONSTRAINT `fk_vehiculos_ref_modelo` 
    FOREIGN KEY (`id_modelo`) 
    REFERENCES `modelos`(`id_modelo`);

-- Para la tabla modelos:
ALTER TABLE `modelos`
  ADD CONSTRAINT `fk_modelos_ref_marca` 
    FOREIGN KEY (`id_marca`) 
    REFERENCES `marcas`(`id_marca`);

-- Para la tabla rol_funcionalidad:
ALTER TABLE `rol_funcionalidad`
  ADD CONSTRAINT `fk_rol_funcionalidad_ref_rol` 
    FOREIGN KEY (`id_rol`) 
    REFERENCES `roles`(`id_rol`),
  ADD CONSTRAINT `fk_rol_funcionalidad_ref_funcionalidad` 
    FOREIGN KEY (`id_funcionalidad`) 
    REFERENCES `funcionalidades`(`id_funcionalidad`);

-- Para la tabla alertas:
ALTER TABLE `alertas`
  ADD CONSTRAINT `fk_alertas_ref_formulario`
    FOREIGN KEY (`id_formulario`)
    REFERENCES `formularios_preoperacionales`(`id_formulario_preoperacional`),
  ADD CONSTRAINT `fk_alertas_ref_respuesta` 
    FOREIGN KEY (`id_respuesta`) 
    REFERENCES `respuestas`(`id_respuesta`),
  ADD CONSTRAINT `fk_alertas_ref_observacion` 
    FOREIGN KEY (`id_observacion`) 
    REFERENCES `observaciones`(`id_observacion`),
  ADD CONSTRAINT `fk_alertas_ref_estado_alerta` 
    FOREIGN KEY (`id_estado_alerta`) 
    REFERENCES `estados_alerta`(`id_estado_alerta`);

-- Para la tabla formularios_preoperacionales:
ALTER TABLE `formularios_preoperacionales`
  ADD CONSTRAINT `fk_formularios_ref_vehiculo` 
    FOREIGN KEY (`id_vehiculo`) 
    REFERENCES `vehiculos`(`id_vehiculo`),
  ADD CONSTRAINT `fk_formularios_ref_usuario` 
    FOREIGN KEY (`id_usuario`) 
    REFERENCES `usuarios`(`id_usuario`);

-- Para la tabla seccion_tipo_vehiculo:
ALTER TABLE `seccion_tipo_vehiculo`
  ADD CONSTRAINT `fk_seccion_tipo_ref_seccion` 
    FOREIGN KEY (`id_seccion`) 
    REFERENCES `secciones`(`id_seccion`),
  ADD CONSTRAINT `fk_seccion_tipo_ref_tipo_vehiculo` 
    FOREIGN KEY (`id_tipo_vehiculo`) 
    REFERENCES `tipos_vehiculo`(`id_tipo_vehiculo`);

-- Para la tabla preguntas:
ALTER TABLE `preguntas`
  ADD CONSTRAINT `fk_preguntas_ref_seccion` 
    FOREIGN KEY (`id_seccion`) 
    REFERENCES `secciones`(`id_seccion`);

-- Para la tabla respuestas:
ALTER TABLE `respuestas`
  ADD CONSTRAINT `fk_respuestas_ref_formulario` 
    FOREIGN KEY (`id_formulario`) 
    REFERENCES `formularios_preoperacionales`(`id_formulario_preoperacional`),
  ADD CONSTRAINT `fk_respuestas_ref_pregunta` 
    FOREIGN KEY (`id_pregunta`) 
    REFERENCES `preguntas`(`id_pregunta`);

-- Para la tabla observaciones:
ALTER TABLE `observaciones`
  ADD CONSTRAINT `fk_observaciones_ref_formulario` 
    FOREIGN KEY (`id_formulario`) 
    REFERENCES `formularios_preoperacionales`(`id_formulario_preoperacional`),
  ADD CONSTRAINT `fk_observaciones_ref_seccion` 
    FOREIGN KEY (`id_seccion`) 
    REFERENCES `secciones`(`id_seccion`);