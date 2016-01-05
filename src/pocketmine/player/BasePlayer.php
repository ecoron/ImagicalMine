<?php
namespace pocketmine\player;


use pocketmine\entity\Human;
use pocketmine\inventory\SimpleTransactionGroup;
use pocketmine\math\Vector3;
use pocketmine\network\SourceInterface;


class BasePlayer extends Human
{
    const SURVIVAL = 0;
    const CREATIVE = 1;
    const ADVENTURE = 2;
    const SPECTATOR = 3;
    const VIEW = BasePlayer::SPECTATOR;
    const SURVIVAL_SLOTS = 36;
    const CREATIVE_SLOTS = 112;

    /**
     * @var array
     */
    public $achievements = array();
    public $blocked      = false;
    public $craftingType = 0; // 0 = 2x2 crafting, 1 = 3x3 crafting, 2 = stonecutter
    public $creationTime = null;
    public $clientID = null;
    public $clientSecret;
    /**
     * @var pocketmine\inventory\SimpleTransactionGroup
     */
    protected $currentTransaction = null;
    public $gamemode;

    /**
     * @var pocketmine\network\SourceInterface
     */
    protected $interface;
    /**
     * @var string
     */
    public $ip = null;
    protected $isCrafting = false;
    /**
     * @var integer
     */
    public $lastBreak = PHP_INT_MAX;
    public $lastCorrect;
    public $loggedIn = false;
    /**
     * @var integer
     */
    protected $messageCounter = 2;
    /**
     * @var int
     */
    public $port = null;
    public $rawUUID = null;
    protected $sendIndex = 0;
    public    $spawned = false;
    /**
     * @var pocketmine\math\Vector3
     */
    public $spawnPosition = null;
    /**
     * @var pocketmine\math\Vector3
     */
    public $speed = null;
    public $uuid = null;
    /**
     * @var \SplObjectStorage<Inventory>
     */
    protected $windows;
    protected $windowCnt = 2;
    /**
     * @var array()
     */
    protected $windowIndex = array();


    /**
     *
     * @param SourceInterface $interface
     * @param null $clientID
     * @param string $ip
     * @param integer $port
     */
    public function __construct(SourceInterface $interface, $clientID, $ip, $port){

        $this->clientID = $clientID;
        $this->creationTime = microtime(true);
        $this->interface = $interface;
        $this->ip = $ip;
        $this->port = $port;
        $this->server = Server::getInstance();
        $this->windows = new \SplObjectStorage();

        $this->initAttributesByClass();
        $this->initAttributesByServer();
    }

    /**
     * initAttributesByClass
     * initialize attributes based on other classes
     */
    protected function initAttributesByClass(){
        $this->attribute = new AttributeManager($this);
        $this->attribute->init();
        $this->boundingBox = new AxisAlignedBB(0, 0, 0, 0, 0, 0);
        $this->loaderId = Level::generateChunkLoaderId($this);
        $this->namedtag = new Compound();
        $this->newPosition = new Vector3(0, 0, 0);
        $this->perm = new PermissibleBase($this);
    }

    /**
     * initAttributesByServer
     * initialize attributes based on server properties
     */
    protected function initAttributesByServer(){
        $this->chunksPerTick = (int) $this->server->getProperty("chunk-sending.per-tick", 4);
        $this->spawnThreshold = (int) $this->server->getProperty("chunk-sending.spawn-threshold", 56);
        $this->gamemode = $this->server->getGamemode();
        $this->viewDistance = $this->server->getViewDistance();

        $this->setLevel($this->server->getDefaultLevel());
    }
}
