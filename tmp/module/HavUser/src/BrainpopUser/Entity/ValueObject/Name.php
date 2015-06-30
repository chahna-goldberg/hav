<?php

namespace BrainpopUser\Entity\ValueObject;

/**
 * Object Value that hold a name
 */
class Name
{
    /** @var string */
    private $privateName;

    /** @var string */
    private $familyName;

    /**
     * initialize the Value Object
     * @param string $privateName
     * @param string $familyName
     */
    public function __construct($privateName, $familyName)
    {
        $this->setPrivateName($privateName);
        $this->setFamilyName($familyName);
    }

    /**
     * Set the private name
     * @param string $name
     * @return Name
     * @throws \RuntimeException
     */
    private function setPrivateName($name)
    {
        if (isset($this->privateName)) {
            throw new \RuntimeException("Private name is already set and is imutable");
        }
        $this->privateName = $name;
        return $this;
    }

    /**
     * Get the private name
     * @return string
     */
    public function getPrivateName()
    {
        return $this->privateName;
    }

    /**
     * Set the family name
     * @param string $name
     * @return Name
     * @throws \RuntimeException
     */
    private function setFamilyName($name)
    {
        if (isset($this->familyName)) {
            throw new \RuntimeException("Family name is already set and is imutable");
        }
        $this->familyName = $name;
        return $this;
    }

    /**
     * Get the family name
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Get the full name
     * @return string
     */
    public function getFullName()
    {
        return $this->privateName . ' ' . $this->familyName;
    }

    /**
     * Validate a given name
     * @param string $name
     * @return Name
     * @throws \InvalidArgumentException
     */
    private function validate($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('An empty name was supplied for a user');
        }

        if (\strlen($name) < 2) {
            throw new \InvalidArgumentException("Supplied name: {$name} is invalid");
        }
        return $this;
    }

}
