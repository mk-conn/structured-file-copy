<?php


namespace MkConn\StructuredFileCopy;


use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Store
 *
 * @package MkConn\FileMover
 */
class Storage
{
    /**
     *
     */
    const DATE_YEAR = 'date:month';
    /**
     *
     */
    const DATE_MONTH = 'date:month';
    /**
     *
     */
    const DATE_DAY = 'date:day';
    /**
     *
     */
    const ALPHA_NAME = 'alpha:name';
    
    /**
     *
     */
    const NAME_LETTERS = 'name:letters';
    /**
     * @var array
     */
    protected $files;
    
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;
    /**
     * @var string
     */
    protected $target;
    /**
     * @var array
     */
    protected $errors = [];
    /**
     * @var bool
     */
    protected $sortInYear = false;
    /**
     * @var bool
     */
    protected $sortInMonths = false;
    /**
     * @var bool
     */
    protected $sortInDays = false;
    
    /**
     * @var bool
     */
    protected $sortNamed = false;
    
    /**
     * @var int
     */
    protected $countNameLetters = 1;
    /**
     * @var int
     */
    protected $totalFiles = 0;
    /**
     * @var int
     */
    protected $totalFileSize = 0;
    /**
     * @var array
     */
    protected $copiedFiles = [];
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;
    /**
     * @var ProgressBar
     */
    protected $progressBar;
    /**
     * @var int
     */
    protected $filesToCopy = 0;
    
    /**
     * Store constructor.
     *
     * @param  array  $files
     * @param  array  $options
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     */
    public function __construct(array $files, array $options, OutputInterface $output)
    {
        $this->files = $files;
        $this->filesToCopy = count($this->files);
        $this->filesystem = new Filesystem();
        $this->output = $output;
        
        $this->setOptions($options);
        $this->prepareFiles();
    }
    
    /**
     *
     */
    public function copy()
    {
        
        $this->progressBar = new ProgressBar($this->output, $this->filesToCopy);
        $this->progressBar->start();
        
        try {
            $targetFolder = $this->target;
            if ($this->sortInYear) {
                foreach ($this->files as $year => $filesPerYear) {
                    if (!$this->filesystem->exists($this->target.'/'.$year)) {
                        
                        $this->filesystem->mkdir($targetFolder);
                    }
                    $targetFolder = $this->target.'/'.$year;
                    if ($this->sortInMonths) {
                        foreach ($filesPerYear as $month => $filesPerMonth) {
                            $targetFolder = $this->target.'/'.$year.'/'.$month;
                            if (!$this->filesystem->exists($targetFolder)) {
                                $this->filesystem->mkdir($targetFolder);
                            }
                            
                            foreach ($filesPerMonth as $filePerMonthName => $file) {
                                $this->copyFile($filePerMonthName, $targetFolder, $file);
                            }
                        }
                    } else {
                        foreach ($filesPerYear as $filePerYearName => $file) {
                            $this->copyFile($filePerYearName, $targetFolder, $file);
                        }
                    }
                }
            } else {
                foreach ($this->files as $fileName => $file) {
                    $this->copyFile($fileName, $targetFolder, $file);
                }
            }
            
            $this->progressBar->finish();
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * @return int
     */
    public function getTotalFiles()
    {
        return $this->totalFiles;
    }
    
    /**
     * @return int
     */
    public function getTotalFileSize()
    {
        return $this->totalFileSize;
    }
    
    /**
     * @return array
     */
    public function getCopiedFiles()
    {
        return $this->copiedFiles;
    }
    
    /**
     * @param  string  $from  Full original file path
     * @param  string  $to  Target folder
     * @param  \SplFileInfo  $file  The SplFileInfo object
     * @param  bool  $preserveTime
     */
    protected function copyFile(string $from, string $to, \SplFileInfo $file, $preserveTime = true)
    {
        $this->copiedFiles[$from] = [
            'to'     => $to.'/'.$file->getFilename(),
            'result' => null
        ];
        try {
            if ($this->filesystem->exists($to.'/'.$file->getFilename())) {
                $this->copiedFiles[$from]['result'] = '<error>Not copied because it already exists</error>';
                
                return 0;
            }
            $this->filesystem->copy($from, $to.'/'.$file->getFilename());
            
            if ($preserveTime) {
                $this->filesystem->touch(
                    $to.'/'.$file->getFilename(),
                    $file->getMTime(),
                    $file->getATime()
                );
            }
            $this->totalFileSize += $file->getSize();
            $this->totalFiles++;
            $this->copiedFiles[$from]['result'] = "<comment>Copied {$to}/{$file->getFilename()}</comment>";
        } catch (\Exception $e) {
            $this->errors[$file->getFilename()] = $e->getMessage();
        }
        $this->progressBar->advance();
    }
    
    /**
     * @param  array  $options
     */
    protected function setOptions(array $options)
    {
        $this->target = $options['target'];
        
        if (in_array(self::DATE_YEAR, $options['sort'])) {
            $this->sortInYear = true;
        }
        if (in_array(self::DATE_MONTH, $options['sort'])) {
            $this->sortInMonths = true;
        }
        if (in_array(self::DATE_DAY, $options['sort'])) {
            $this->sortInDays = true;
        }
        if (in_array(self::ALPHA_NAME, $options['sort'])) {
            $this->sortNamed = true;
            
            if (isset($options[self::NAME_LETTERS])) {
                $this->countNameLetters = $options['sort'][self::NAME_LETTERS];
            }
        }
    }
    
    /**
     * @param $files
     *
     * @return array
     */
    protected function byYear($files)
    {
        $return = [];
        foreach ($files as $fileName => $file) {
            $return[date('Y', $file->getMTime())][$fileName] = $file;
        }
        
        return $return;
    }
    
    /**
     * @param $files
     *
     * @return array
     */
    protected function byMonth($files)
    {
        $return = [];
        foreach ($files as $fileName => $file) {
            $return[date('m', $file->getMTime())][$fileName] = $file;
        }
        
        return $return;
    }
    
    protected function byDay($files)
    {
    
    }
    
    protected function byName($files, $countLetters = 1)
    {
    
    }
    
    /**
     *
     */
    protected function prepareFiles()
    {
        $files = [];
        if ($this->sortInYear) {
            $files = $this->byYear($this->files);
        }
        
        if ($this->sortInMonths) {
            if ($this->sortInYear) {
                foreach ($files as $year => $filesPerYear) {
                    $files[$year] = $this->byMonth($filesPerYear);
                }
            } else {
                $files = $this->byMonth($files);
            }
        }
        
        if ($this->sortInDays) {
        
        }
        
        if (!empty($files)) {
            $this->files = $files;
        }
        
    }
}