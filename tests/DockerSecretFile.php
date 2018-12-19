<?php
namespace Tests;

use SplFileObject;
use DirectoryIterator;

/**
 * Docker secret file manager for tests.
 *
 * @package Tests
 */
class DockerSecretFile extends SplFileObject
{
    /**
     * Create a docker secret file populating it with the passed value.
     *
     * @param   string  $secret
     * @param   string  $value
     *
     * @return  void
     */
    public function __construct($secret, $value = '')
    {
        parent::__construct(self::dockerSecretStoragePath($secret), 'w+');

        $this->fwrite($value);
    }

    /**
     * Get the environments docker secret file storage path.
     *
     * @param   string  $secret     Optionally pass a secret name to create the file.
     *
     * @return  string
     */
    public static function dockerSecretStoragePath($secret = '')
    {
        return __DIR__ . '/fixtures/secrets/' . $secret;
    }

    /**
     * Cleanup the docker secrets storage path.
     *
     * @return  void
     */
    public static function cleanup()
    {
        foreach (new DirectoryIterator(self::dockerSecretStoragePath()) as $file) {
            if ($file->isDot() || $file->getFilename() === '.gitignore') {
                continue;
            }
            unlink($file->getRealPath());
        }
    }

    /**
     * Get the content of the docker secret file.
     *
     * @return  string
     */
    public function content()
    {
        return (string) $this;
    }

    /**
     * Get the realpath of the docker secret file.
     *
     * @return  string
     */
    public function path()
    {
        return $this->getRealPath();
    }

    /**
     * Convert the file to a string.
     *
     * @return  string
     */
    public function __toString()
    {
        $this->rewind();
        return $this->fread($this->getSize());
    }
}
