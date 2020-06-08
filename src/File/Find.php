<?php


namespace MkConn\StructruedFileCopy\File;


use Symfony\Component\Finder\Finder;
use Symfony\Component\Mime\MimeTypes;

/**
 * Class Ext
 *
 * @package MkConn\FileMover\File
 */
class Find
{
    
    protected static $typeMap = [
        'pdf'        => ['application/pdf'],
        'postscript' => ['application/postscript'],
        'richtext'   => ['application/rtf'],
        'application/stuffit',
        'office'     => [
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        'application/x-apple-diskimage',
        'font'       => ['application/x-font-truetype'],
        'archive'    => [
            'application/x-bz2',
            'application/x-gzip',
            'application/x-java-archive',
        ],
        'application/x-ms-dos-executable',
        'application/x-msaccess',
        'php'        => ['application/x-php'],
        'application/x-rar-compressed',
        'application/x-stuffit',
        'application/x-tar',
        'application/xml',
        'application/zip',
        'audio'      => [
            'audio/midi',
            'audio/midi',
            'audio/mp4',
            'audio/mpeg',
            'audio/ogg',
            'audio/wav',
            'audio/x-aiff',
            'audio/x-mpegurl',
            'audio/x-ms-wma',
            'audio/x-ms-wmv'
        ],
        'image'      => [
            'image/bmp',
            'image/gif',
            'image/jpeg',
            'image/jpeg',
            'image/png',
            'image/svg+xml',
            'image/tiff',
            'image/tiff'
        ],
        'message'    => ['message/rfc822'],
        'text'       => [
            'text/css',
            'text/csv',
            'text/html',
            'text/html',
            'text/javascript',
            'text/plain',
            'text/x-vcard',
        ],
        'video'      => [
            'video/avi',
            'video/mp4',
            'video/mpeg',
            'video/mpeg',
            'video/quicktime',
            'video/x-flv',
        ]
    
    ];
    
    /**
     * @var string
     */
    protected $root;
    /**
     * @var array
     */
    protected $types;
    /**
     * @var array
     */
    protected $exts = [];
    /**
     * @var array
     */
    protected $exclude = [];
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;
    
    /**
     * Find constructor.
     *
     * @param  string  $root
     * @param  array  $types
     * @param  array  $exts
     * @param  array  $exclude
     */
    public function __construct(string $root, array $types = [], array $exts = [], array $exclude = [])
    {
        $this->root = $root;
        $this->exts = $exts;
        $this->types = $types;
        $this->exclude = $exclude;
        $this->finder = new Finder();
        
        $this->prepareExtensions();
    }
    
    /**
     * @param $type
     *
     * @return mixed|null
     */
    protected static function findMimeTypes($type)
    {
        if (isset(self::$typeMap[$type])) {
            return self::$typeMap[$type];
        }
        
        return null;
    }
    
    /**
     *
     */
    public function files()
    {
        return $this->finder->name($this->exts)
                            ->files()
                            ->in($this->root);
    }
    
    /**
     *
     */
    protected function prepareExtensions()
    {
        if (empty($this->exts) && empty($this->types)) {
            return;
        }
        
        if (!empty($this->types)) {
            $mimeTypes = new MimeTypes();
            $exts = [];
            $types = [];
            foreach ($this->types as $type) {
                if (strpos($type, '/') === false) {
                    $typesFromMap = self::findMimeTypes($type);
                    if ($typesFromMap) {
                        $types = $typesFromMap;
                    }
                } else {
                    $types = [$type];
                }
                
                foreach ($types as $type) {
                    $exts = array_merge($exts, $mimeTypes->getExtensions($type));
                }
            }
            
            $this->exts = array_merge($this->exts, $exts);
            $this->exts = array_diff($this->exts, $this->exclude);
        }
        
        array_walk($this->exts, function (&$ext) {
            $ext = "/\.$ext/i";
        });
    }
}