<?php

namespace App\Transformers;

use App\Models\Ico;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Carbon;

class IcoTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'members',
        'roles'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Ico $ico)
    {
        return [
            'id'                    => $ico->id,
            'name'                  => $ico->name,
            'symbol'                => $ico->symbol,
            'total_supply_token'    => $ico->total_supply_token,
            'stage'                 => $ico->stage,
            'launch_date' => Carbon::parse($ico->launch_date)->format('Y-m-d'),
            'initial_price'         => (double) $ico->initial_price,
            'short_description'     => $ico->short_description,
            'full_description'      => $ico->full_description,
            'website_url'           => $ico->website_url,
            'whitepaper_url'        => $ico->whitepaper_url,
            'twitter_url'           => $ico->twitter_url,
            'facebook_url'          => $ico->facebook_url,
            'telegram_url'          => $ico->telegram_url,
            'bitcointalk_url'       => $ico->bitcointalk_url,
            'official_video_url'    => $ico->official_video_url
        ];
    }

    public function includeMembers(Ico $ico)
    {
        return $this->collection($ico->members, new UserTransformer());
    }

    public function includeRoles(Ico $ico)
    {
        return $this->collection($ico->roles, new IcoRoleTransformer());
    }
}
