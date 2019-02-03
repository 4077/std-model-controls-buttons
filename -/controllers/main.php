<?php namespace std\modelControls\buttons\controllers;

class Main extends \Controller
{
    private $model;

    public function __create()
    {
        $model = $this->model = $this->data['model'];

        $this->instance_(underscore_model($model));

        $this->dmap('|' . underscore_model($model), 'data');
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $model = $this->model;
        $modelXPack = xpack_model($this->model);

        $buttons = $this->data('data/buttons');

        foreach ($buttons as $action => $buttonData) {
            if ($call = ap($buttonData, 'call') and isset($call[0])) {
                $path = $call[0];
                $data = $call[1] ?? [];

                $data[$this->data('data/model_index') ?: 'model'] = $modelXPack;

                $v->assign('button', [
                    'CONTENT' => $this->c('\std\ui button:view', [
                        'path'  => $path,
                        'data'  => $data,
                        'class' => 'button ' . ($buttonData['class'] ?? ''),
                        'icon'  => $buttonData['icon'] ?? false,
                        'title' => $buttonData['title'] ?? false
                    ])
                ]);
            } elseif ($href = ap($buttonData, 'href')) {
                $href = str_replace('%id', $model->id, $href);

                $v->assign('button', [
                    'CONTENT' => $this->c('\std\ui tag:view:a', [
                        'content' => $buttonData['icon'] ?? false ? '<i class="icon ' . $buttonData['icon'] . '"></i>' : '',
                        'attrs'   => [
                            'class'  => 'button ' . ($buttonData['class'] ?? ''),
                            'href'   => $href,
                            'title'  => $buttonData['title'] ?? false,
                            'target' => $buttonData['target'] ?? '_self',
                            'hover'  => 'hover'
                        ]
                    ])
                ]);
            }
        }

        $this->css();

        return $v;
    }
}
