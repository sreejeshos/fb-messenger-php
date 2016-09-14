<?php
class ItineraryMessage extends Message
{
    /**
     * Structured message button type
     */
    const TYPE_BUTTON = "button";
    /**
     * Structured message generic type
     */
    const TYPE_GENERIC = "generic";
    /**
     * Structured message receipt type
     */
    const TYPE_RECEIPT = "receipt";
    /**
     * Structured message airlineitenary type
     */
    const TYPE_AIRLINE_ITINERARY = "airline_itinerary";

	protected  $intro_message= null;
	protected  $locale= null;
	protected  $pnr_number= null;
	protected  $base_price= null;
	protected  $tax= null;
	protected  $total_price= null;
    /**
     * @var null|string
     */
    protected $type = null;
    /**
     * @var null|string
     */
    protected $title = null;
    /**
     * @var null|string
     */
    protected $subtitle = null;
    /**
     * @var array
     */
    protected $elements = [];
    /**
     * @var array
     */
    protected $passenger_info = [];

    protected $buttons = [];
    /**
     * @var null|string
     */
    protected $recipient_name = null;
    /**
     * @var null|integer
     */
    protected $order_number = null;
    /**
     * @var string
     */
    protected $currency = "USD";
    /**
     * @var null|string
     */
    protected $payment_method = null;
    /**
     * @var null|string
     */
    protected $order_url = null;
    /**
     * @var null|integer
     */
    protected $timestamp = null;
    /**
     * @var array
     */
    protected $address = [];
    /**
     * @var array
     */
    protected $summary = [];
    /**
     * @var array
     */
    protected $adjustments = [];
    /**
     * StructuredMessage constructor.
     *
     * @param $recipient
     * @param $type
     * @param $data
     */
    public function __construct($recipient, $type, $data)
    {
        $this->recipient = $recipient;
        $this->type = $type;
        switch ($type)
        {
            case self::TYPE_BUTTON:
                $this->title = $data['text'];
                $this->buttons = $data['buttons'];
            break;
            case self::TYPE_GENERIC:
                $this->elements = $data['elements'];
            break;
            case self::TYPE_RECEIPT:
                $this->recipient_name = $data['recipient_name'];
                $this->order_number = $data['order_number'];
                $this->currency = $data['currency'];
                $this->payment_method = $data['payment_method'];
                $this->order_url = $data['order_url'];
                $this->timestamp = $data['timestamp'];
                $this->elements = $data['elements'];
                $this->address = $data['address'];
                $this->summary = $data['summary'];
                $this->adjustments = $data['adjustments'];
            break;
	  case self::TYPE_AIRLINE_ITINERARY: 
		$this->intro_message = $data['intro_message'];
		$this->locale = $data['locale'];
		$this->pnr_number = $data['pnr_number'];
		$this->base_price = $data['base_price'];
		$this->tax = $data['tax'];
		$this->total_price = $data['total_price'];
		$this->currency = $data['currency'];
		$this->passenger_info = $data['passenger_info'];
		break;

		
        }
    }
    /**
     * Get Data
     *
     * @return array
     */
    public function getData()
    {
        $result = [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => $this->type
                ]
            ]
        ];
        switch ($this->type)
        {
            case self::TYPE_BUTTON:
                $result['attachment']['payload']['text'] = $this->title;
                $result['attachment']['payload']['buttons'] = [];
                foreach ($this->buttons as $btn) {
                    $result['attachment']['payload']['buttons'][] = $btn->getData();
                }
            break;
            case self::TYPE_GENERIC:
                $result['attachment']['payload']['elements'] = [];
                foreach ($this->elements as $btn) {
                    $result['attachment']['payload']['elements'][] = $btn->getData();
                }
            break;
            case self::TYPE_RECEIPT:
                $result['attachment']['payload']['recipient_name'] = $this->recipient_name;
                $result['attachment']['payload']['order_number'] = $this->order_number;
                $result['attachment']['payload']['currency'] = $this->currency;
                $result['attachment']['payload']['payment_method'] = $this->payment_method;
                $result['attachment']['payload']['order_url'] = $this->order_url;
                $result['attachment']['payload']['timestamp'] = $this->timestamp;
                $result['attachment']['payload']['elements'] = [];
                foreach ($this->elements as $btn) {
                    $result['attachment']['payload']['elements'][] = $btn->getData();
                }
                $result['attachment']['payload']['address'] = $this->address->getData();
                $result['attachment']['payload']['summary'] = $this->summary->getData();
                $result['attachment']['payload']['adjustments'] = [];
                foreach ($this->adjustments as $btn) {
                    $result['attachment']['payload']['adjustments'][] = $btn->getData();
                }
            break;
	    case self::TYPE_AIRLINE_ITINERARY:
		
		$result['attachment']['payload']['intro_message'] = $this->intro_message;	
		$result['attachment']['payload']['locale'] = $this->locale;	
		$result['attachment']['payload']['pnr_number'] = $this->pnr_number;	
		$result['attachment']['payload']['base_price'] = $this->base_price;	
		$result['attachment']['payload']['tax'] = $this->tax;	
		$result['attachment']['payload']['total_price'] = $this->total_price;	
		$result['attachment']['payload']['currency'] = $this->currency;
		$result['attachment']['payload']['passenger_info'] = [];	
		foreach($this->passenger_info as $psgr) {
			$result['attachment']['payload']['passenger_info'][] = $psgr->getData();
		}
		
		$result['attachment']['payload']['flight_info'] = [];
		foreach($this->passenger_info as $btn) {
			$result['attachment']['payload']['flight_info'][] = $btn->getData();
		}

        break;
        return [
            'recipient' =>  [
                'id' => $this->recipient
            ],
            'message' => $result
        ];
    }
}
?>
