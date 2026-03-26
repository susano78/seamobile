<?php

function get_datos_empresa($empresaid){
    $sql="select 
           nombre nombre_empresa
          ,cif cif_empresa
          ,direccion direccion_empresa
          ,poblacion poblacion_empresa
          ,codigo_postal codigo_postal_empresa
          ,telefono telefono_empresa
          ,cuenta_iban cuenta_iban_empresa
          ,norma19_bic norma19_bic_empresa
          ,pie_factura pie_factura_empresa
          ,norma19_sufijo norma19_sufijo_empresa
          ,datos_registrales datos_registrales_empresa
          ,porc_retencion_irpf porc_retencion_irpf_empresa
          ,codigo_a3con codigo_a3con_empresa
          ,importe_afiliado importe_afiliado_empresa
          ,importe_cliente importe_cliente_empresa
          ,definicion_calificado definicion_calificado_empresa
          ,condiciones_afiliado condiciones_afiliado_empresa
          ,paypal_client_id paypal_client_id_empresa
          ,paypal_client_secret paypal_client_secret_empresa
          ,nombre_ext nombre_empresa_ext
          ,cif_ext cif_empresa_ext
          ,direccion_ext direccion_empresa_ext
          ,poblacion_ext poblacion_empresa_ext
          ,codigo_postal_ext codigo_postal_empresa_ext
          ,telefono_ext telefono_empresa_ext
          ,swift_bic cuenta_swift_bic_empresa
          from empresas
          where id_e=".$empresaid;

     return get_registros($sql);
   }

  

?>
