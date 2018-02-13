<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $('#tiket-id_formapago').on('change',function() {        
        
        if ($(this).val() == '1'){           
            $('#tiket-cuentapagadora').val('1');
        }else{           
            $('#tiket-cuentapagadora').prop('selectedIndex','1')
            $('#tiket-cuentapagadora').val('2');
        }
    });
    
    $('#tiket-cuentapagadora').on('change',function() {
        $('#divcheque').css('display','none');

        if ( ($(this).val() == '1') || ($(this).val() == '2') ){
            $('#tiket-tipo_pago').val('1');
        }
    });

