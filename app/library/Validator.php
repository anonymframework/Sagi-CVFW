<?php


class Validator
{

    /**
     * @var array
     */
    protected $rules;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var array
     */
    protected $filtredDatas;

    /**
     * @var array
     */
    protected $messages = [
        'required' => ':field alanı doldurulması zorunludur.',
        'min' => ':field alanına girilebilecek en küçük değer :min',
        'max' => ':field alanına girilebilecek en büyük değer : max',
        'between' => ':field alanına girilebilecek değerler :min - :max aralığında olmadılıdır.',
        'min_digit' => ':field alanına en düşük :min karekterli bir yazı girebilirsiniz.',
        'max_digit' => ':field alanına en büyük :max karekterli bir yazı girebilirsiniz.'
    ];

    /**
     * @var array
     */
    protected $datas;

    /**
     * Validator constructor.
     * @param array $datas
     * @param array $rules
     * @param array $filters
     * @param array $messages
     */
    public function __construct($datas = [], $rules = [], $filters = [], $messages = [])
    {
        $this->setDatas($datas)->setRules($rules)->setFilters($filters)->setMessages($messages);
    }

    public function validate()
    {
        $rules = $this->getRules();
        $filters = $this->getFilters();

        $this->handleFilters($filters);
        $this->handleRules($rules);
    }

    /**
     * @param array $filters
     */
    private function handleFilters(array $filters)
    {
        foreach ($filters as $index => $subFilters) {
            $subFilters = explode("|", $subFilters);

            foreach ($subFilters as $subFilter) {
                $filterFunc = 'handleFilter' . ucfirst($subFilter);

                $filtred = call_user_func_array(array($this, $filterFunc), [$this->datas[$index]]);
                $this->datas[$index] = $filtred;
            }
        }
    }

    protected function handleFilterXss($data)
    {

    }

    protected function handleFilterStrip_tags($data)
    {

    }

    /**
     * @param array $rules
     */
    private function handleRules(array $rules)
    {
        foreach ($rules as $index => $value) {
            $ex = explode("|", $value);

            foreach ($ex as $item) {
                $func = "handleRule" . ucfirst($item);

                call_user_func_array(array($this, $func), [$index, $this->getParams($item)]);
            }
        }
    }

    /**
     * @param $item
     * @return array
     */
    private function getParams($item)
    {
        if (strpos($item, ":") !== false) {
            $args = explode(":", $item)[1];

            return explode(",", $args);
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     * @return Validator
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return Validator
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return Validator
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @return array
     */
    public function getDatas()
    {
        return $this->datas;
    }

    /**
     * @param array $datas
     * @return Validator
     */
    public function setDatas($datas)
    {
        $this->datas = $datas;
        return $this;
    }


}