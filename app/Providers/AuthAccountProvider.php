<?php

namespace App\Providers;

use App\Http\Controllers\Traits\CtrlDebugTrait;
use Illuminate\Auth\EloquentUserProvider;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Support\Arrayable;
use Closure;

//use Illuminate\Support\Facades\Log;

/**
 * 独自のログイン認証のプロバイダー(accountテーブル対応)
 */
class AuthAccountProvider extends EloquentUserProvider
{
    use CtrlDebugTrait;

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {

        // MEMO
        // この関数は、非同期通信でも毎回呼ばれていた。
        // ユーザテーブル変更について、複合キーと$identifierがアンダーバー区切りだが
        // 一通り動作確認とソースはOK。試行回数エラーもOK。remember_tokenもOK
        // SESSIONの中身を確認したが、きちんとアンダーバー区切りのIDが格納されていた

        // モデルの作成。一応直接AccountPasswordモデルを参照せず、auth.phpで設定されたモデルを参照するような形
        $model = $this->createModel();

        // キー
        $primaryKey = $model->getKeyName();

        // クエリを取得
        $query = $this->newModelQuery($model);

        // キー条件を追加
        $query->where($primaryKey, $identifier);

        // 最低限の情報のみ取得
        // $query->select('user_id', 'account_type', 'remember_token');
        // $query->select('user_id', 'account_role_id', 'account_name');
        $query->select('user_id');

        // 1件取得
        $resultAccount = $query->firstOrFail();

        // Log::debug($resultAccount);
        $user_id = $resultAccount['user_id'];

        // Log::debug(DB::getQueryLog());

        // ユーザ情報を取得
        $user_role = UserRole::select(
                'user_roles.role_type_id',
                'user_roles.authority_registration',
                'user_roles.authority_change_delete',
                'user_roles.authority_refer',
                'user_roles.authority_download',
                'users.user_name'
                )
            ->sdJoin(User::class, function ($join) {
                $join->on('user_roles.user_role_id', 'users.user_role_id');
            })
            ->where('users.user_id', '=', $user_id)
            ->firstOrFail();

        $resultAccount['role_type_id'] = $user_role['role_type_id'];
        $resultAccount['is_registration'] = $user_role['authority_registration'];
        $resultAccount['is_changedel'] = $user_role['authority_change_delete'];
        $resultAccount['is_refer'] = $user_role['authority_refer'];
        $resultAccount['is_download'] = $user_role['authority_download'];
        $resultAccount['name'] = $user_role['user_name'];

        // アカウント情報を返却
        return $resultAccount;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {

        // 「パスワードを記憶」時のトークンの取得
        // この関数も複合キー対応。
        // retrieveByIdと同じことやるので、関数直接呼んだ
        $retrievedModel = $this->retrieveById($identifier);

        if (!$retrievedModel) {
            return;
        }

        $rememberToken = $retrievedModel->getRememberToken();

        return $rememberToken && hash_equals($rememberToken, $token)
            ? $retrievedModel : null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Model  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        // 「パスワードを記憶」時のトークンの更新
        // retrieveByIdでnameを追加すると、model->save()でエラーになる・・
        // nameは消す。nameが入ってくるのは、ログアウト時。
        // ログイン時はnameがなく、普通のaccoutしか来ない
        // なのでとりあえずnameは消すでよい
        unset($user->{"name"});
        // unset($user->{"campus_cd"});

        // 元の処理を呼ぶ
        parent::updateRememberToken($user, $token);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // ログイン条件追加のため、retrieveByCredentials をラップする

        $credentials = array_filter(
            $credentials,
            fn ($key) => !str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } elseif ($value instanceof Closure) {
                $value($query);
            } else {
                $query->where($key, $value);
            }
        }
        return $query->first();
    }
}
