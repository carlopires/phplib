<?php
/*
 * Algumas funções auxiliares para strings
 * 
 * Carlo Pires, em 07/03/2013
 */


/*
 * Retorna uma string com o espaçamento desejado à direita.
 * É util geralmente na geração de relatorios e saídas com
 * formatação pré-fixada.
 * 
 * Exemplos:
 *   rpad("A", 3)   => "A  "
 *   rpad("ABC", 3) => "ABC"
 *   rpad("ABC", 5) => "ABC  "
 *   rpad("ABCD",5) => "ABCD "
 *   
 *   rpad("corte aqui", 20, '-') => "corte aqui----------"
 *   
 * Histórico: 
 * 	criada em 07/03/2013, Carlo Pires
 */
function rpad($string, $size=2, $ch=' ') {
	return str_pad($string, $size, $ch, STR_PAD_LEFT);
}


/*
 * Retorna uma string com o espaçamento desejado à esquerda.
* É util geralmente na geração de relatorios e saídas com
* formatação pré-fixada.
*
* Exemplos:
*   lpad("A", 3)   => "  A"
*   lpad("ABC", 3) => "ABC"
*   lpad("ABC", 5) => "  ABC"
*   lpad("ABCD",5) => " ABCD"
*
*   lpad("corte aqui", 20, '-') => "----------corte aqui"
*
* Histórico:
* 	criada em 07/03/2013, Carlo Pires
*/
function lpad($string, $size=2, $ch=' ') {
	return str_pad($string, $size, $ch, STR_PAD_RIGHT);
}
