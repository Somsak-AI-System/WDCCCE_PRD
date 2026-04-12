<?php

/**
 * Class Debug
 * Auther : Worrachai Jansa (Copy Jukgrid Silathong Campaigns)
 * Description : User For Debug Web COntroller
 */
class Debug
{
  public $arr_debug_api = array();
  public $arr_debug_view = array();
  public $arr_debug_controller = array();

  /**
   * Method View
   * Auther : Worrachai Jansa (Copy Jukgrid Silathong Campaigns)
   * Description : Show View Debug in Page
   * How To : use paramitor method=view inurl and use paramitor debug too.
   * Example : http://music.truelife.com?debug=view
   */
  function view($o_object, $_ci_data)
  {
    foreach(array(
                  '_ci_view',
                  '_ci_vars',
                  '_ci_path',
                  '_ci_return'
    ) as $_ci_val)
    {
      $$_ci_val = (! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
    }
    
    if($_ci_path == '')
    {
      $_ci_file = strpos($_ci_view, '.') ? $_ci_view : $_ci_view . EXT;
      $_ci_path = $o_object->_ci_view_path . $_ci_file;
    }
    else
    {
      $_ci_file = end(explode('/', $_ci_path));
    }
    
    if(DEBUG && DEBUG_VIEW)
    {
      $this->CI = & get_instance();
      if(strpos($_ci_path, 'application/views/layouts') !== FALSE)
      {
        $debug_data['url'] = $_ci_path;
        $debug_data['path'] = $this->CI->router->directory . $this->CI->router->fetch_module();
      }
      else
      {
        $debug_data['url'] = $_ci_path;
        $debug_data['path'] = $this->CI->router->directory . $this->CI->router->fetch_module();
      }
    }
    
    if(isset($_GET['debug']) && strpos($_GET['debug'], 'data') !== FALSE)
    {
      if(isset($_ci_data['_ci_vars']))
      {
        foreach($_ci_data['_ci_vars'] as $k_data => $v_data)
        {
          $vars_data = "Add Varirable $k_data to  View  : ";
          $debug_data['vars'] = $vars_data;
          $debug_data['vars_data'] = htmlspecialchars($v_data);
        }
      }
    }
    
    $this->arr_debug_view[] = $debug_data;
  }

  /**
   * Method Controller
   * Auther : Worrachai Jansa (Copy Jukgrid Silathong Campaigns)
   * Description : Show Controller Debug in Page
   * How To : use paramitor method=controller inurl and use paramitor debug too.
   * Example : http://music.truelife.com?debug=controller
   */
  function controller($args = array())
  {
    if(DEBUG && DEBUG_CONTROLLER)
    {
      foreach($args as $k_data => $v_data)
      {
        $controller_data['url'] = $v_data;
        $this->arr_debug_controller[] = $controller_data;
      }
    }
  }

  /**
   * Method Api
   * Auther : Worrachai Jansa (Copy Jukgrid Silathong Campaigns)
   * Description : Show api Debug in Page
   * How To : use paramitor method=api inurl and use paramitor debug too.
   * Example : http://music.truelife.com?debug=api
   */
  function api($url)
  {
    if(DEBUG_API)
    {
      $this->CI = & get_instance();
      $debug_data['path'] = $this->CI->router->directory . $this->CI->router->fetch_module();
      $debug_data['url'] = $url;
      $this->arr_debug_api[] = $debug_data;
    }
  }

  /**
   * Method Time
   * Auther : Worrachai Jansa (Copy Jukgrid Silathong Campaigns)
   */
  function time()
  {
    if(isset($_GET['debug']) && strpos($_GET['debug'], 'time') !== FALSE)
    {
      echo '<pre style="background-color:SandyBrown;padding:5px;">';
      echo "Load API   : $url";
      echo '</pre>';
    }
  }
}