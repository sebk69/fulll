<?php

namespace Fulll\Domain\Entity\Trait;

trait HasIdentifier
{

    private const ENCODING_CHARS = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

    protected string|null $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function generateId(): self
    {

        $time = (int)microtime(true) * 1000; // Milliseconds since Unix epoch
        $timestamp = self::encodeTime($time, 10);
        $randomness = self::encodeRandom(16);

        $this->id = $timestamp . $randomness;

        return $this;

    }

    /**
     * Encode timestamp into a 10-character ULID string
     *
     * @param int $time
     * @param int $length
     * @return string
     */
    private static function encodeTime(int $time, int $length): string
    {
        $encoded = '';
        for ($i = $length - 1; $i >= 0; $i--) {
            $encoded = self::ENCODING_CHARS[$time % 32] . $encoded;
            $time = (int)($time / 32);
        }

        return $encoded;
    }

    /**
     * Generate 16 characters of random data for ULID
     *
     * @param int $length
     * @return string
     */
    private static function encodeRandom(int $length): string
    {
        $randomBytes = random_bytes($length);
        $encoded = '';

        for ($i = 0; $i < $length; $i++) {
            $encoded .= self::ENCODING_CHARS[ord($randomBytes[$i]) % 32];
        }

        return $encoded;
    }

}