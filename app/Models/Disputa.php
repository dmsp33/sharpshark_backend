<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Publicacion;

/**
 * @SWG\Definition(
 *      definition="Disputa",
 *      required={},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="claim_for",
 *          description="claim_for",
 *          type="string",
 *          
 *      ),
 *      @SWG\Property(
 *          property="jurisdiction",
 *          description="jurisdiction",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="discovered",
 *          description="discovered",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="screenshot",
 *          description="screenshot",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remove-content",
 *          description="remove-content",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="acknowledge",
 *          description="acknowledge",
 *          type="boolean"
 *      ),
 *     @SWG\Property(
 *          property="pay-for-use",
 *          description="pay-for-use",
 *          type="boolean"
 *      ),
 *    @SWG\Property(
 *          property="amount",
 *          description="amount",
 *          type="string"
 *      ),
 *   @SWG\Property(
 *          property="money-type",
 *          description="money-type",
 *          type="string"
 *      ),
 *   @SWG\Property(
 *          property="conditions-default",
 *          description="conditions-default",
 *          type="boolean"
 *      ),
 *   @SWG\Property(
 *          property="certificate_authorship",
 *          description="certificate_authorship",
 *          type="string"
 *      ), 
 *   @SWG\Property(
 *          property="screenshot_draft",
 *          description="screenshot_draft",
 *          type="string"
 *      ),   
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */

class Disputa extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'claim_for',
        'jurisdiction',
        'discovered',
        'screenshot',
        'remove_content',
        'acknowledge',
        'pay_for_use',
        'amount',
        'money_type',
        'conditions_default',
        'certificate_authorship',
        'screenshot_draft',
        'in_question',
        'in_question_web_archive',
        'your_publication',
        'your_web_archive',
        'type',
        'contact_type',
        'email',
        'alerta_id'
    ];

    protected $hidden = [
        'user_id',
        'type',
        'contact_type'
    ];

    public static $rules = [
        'user_id' => 'required',
        'claim_for' => 'required',
        'jurisdiction' => 'required|string',
        'discovered' => 'required|date',
        'screenshot' => 'required|string',
        'remove_content' => 'required|boolean',
        'acknowledge' => 'required_if:remove_content,false|boolean',
        'pay_for_use' => 'required_if:remove_content,false|boolean', 
        'amount' => 'required_if:pay_for_use,true',
        'money_type' => 'required_if:pay_for_use,true|string',
        'conditions_default' => 'boolean|nullable',
        'certificate_authorship' => 'required|string',
        'screenshot_draft' => 'string|nullable',
        'in_question' => 'required|array',
        'in_question.*' => 'required|string',
        'in_question_web_archive' => 'required|array',
        'in_question_web_archive.*' => 'required|string',
        'your_publication' => 'required|array',
        'your_publication.*' => 'required|string',
        'your_web_archive' => 'required|array',
        'your_web_archive.*' => 'required|string',
    ];

    protected $casts = [
        'in_question' => 'array',
        'in_question_web_archive' => 'array',
        'your_publication' => 'array',
        'your_web_archive' => 'array',
        'email' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alerta()
    {
        return $this->belongsTo(Alerta::class);
    }

}
