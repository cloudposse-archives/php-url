<?
/* URL.class.php - Implements a URL object
 * Copyright (C) 2007 Erik Osterman <e@osterman.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/* File Authors:
 *   Erik Osterman <e@osterman.com>
 */

class URL
{
	private $url;
	private $parts;
	public function __construct( $url )
	{
		$this->__set('url', $url);
	}

  public function __destruct()
  {
    unset($this->url);
    unset($this->parts);
  }

	public function __get( $property )
	{
		//print_r($this->parts);
		if( $this->exists($property) )
			return $this->parts[$property];
		else
		{
			switch( $property )
			{
        case 'text':
				case 'str':
				case 'url':
					return $this->url;
				default:
					throw new Exception( get_class($this) . "::$property not handled");
			}
		}
	}
  
  public function __toString()
  {
    return $this->url;
  }

	public function __set( $property, $value )
	{
		switch( $property )
		{
			case 'str':
			case 'url':
				if( ! Type::string($value) )
					throw new Exception( get_class($this) . "::$property should be a well-formatted URL. Got " . Debug::describe( $value ) );
				if( ! URL::valid( $value ) )
					throw new Exception( get_class($this) . "::$property invalid url " . Debug::describe( $value ) );
				$this->url = $value;
				$this->parts = parse_url($value) ;
				return $this;
			default:
				throw new Exception( get_class($this) . "::$property  cannot be set");
				
		}
	}

  public function __key()
  {
    return $this->__toString();
  }

  public function glue()
  {
    $uri = isset($this->parts['scheme']) ? $this->parts['scheme'].':'.((strtolower($this->parts['scheme']) == 'mailto') ? '':'//'): '';
    $uri .= isset($this->parts['user']) ? $this->parts['user'].($this->parts['pass']? ':'.$this->parts['pass']:'').'@':'';
    $uri .= isset($this->parts['host']) ? $this->parts['host'] : '';
    $uri .= isset($this->parts['port']) ? ':'.$this->parts['port'] : '';
    $uri .= isset($this->parts['path']) ? $this->parts['path'] : '';
    $uri .= isset($this->parts['query']) ? '?'.$this->parts['query'] : '';
    $uri .= isset($this->parts['fragment']) ? '#'.$this->parts['fragment'] : '';
    return $uri;
  }
 
  public function exists( $property )
  {
    return isset($this->parts[$property]) || array_key_exists($property, $this->parts);
  }
	
	public static function valid( $url )
	{
		return strstr($url, '://') == true;
	}
	
	public function __unset($property)
	{
		throw new Exception( get_class($this). "::$property cannot be unset");
	}
	
}

/*
   
// Example Usage:
$host = new URL('tcp://apple.com:80');
print $host->host . "\n";
print $host->port . "\n";

*/

?>
