<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

#[Fillable(['name', 'email', 'password', 'username', 'role', 'grade'])]
#[Hidden(['password', 'remember_token'])]

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $grade
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function authenticate(string $password, string $email): bool
    {
        $user = User::where('email', $email)
            ->first(['password']);

        return $user && Hash::check($password, $user->password);
    }

    public static function AddRegisterData($payload)
    {
        return User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
            'username' => $payload['username'],
            'grade' => $payload['grade'],
        ]);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'borrower_id');
    }

    public function handledLoans()
    {
        return $this->hasMany(Loan::class, 'approver_id');
    }

    /**
     * Aturan Bisnis: Kebijakan peminjaman user sesuai jenjang/grade.
     */
    public function loanPolicy(): LoanPolicy
    {
        return LoanPolicy::forGrade($this->grade ?? 'bachelor');
    }

    /**
     * Aturan Bisnis: Hitung total buku aktif yang sedang dipinjam user.
     */
    public function activeLoansCount(): int
    {
        return $this->loans()
            ->whereIn('status', ['pending', 'approved'])
            ->whereNull('returned_at')
            ->count();
    }

    /**
     * Aturan Bisnis: Cek apakah user masih berhak meminjam sejumlah buku tertentu.
     */
    public function canBorrowBooks(int $requestedCount = 1): bool
    {
        $policy = $this->loanPolicy();
        $total = $this->activeLoansCount() + $requestedCount;

        return $total <= $policy->max_borrowed_books;
    }

    /**
     * Aturan Bisnis: Sisa kuota peminjaman buku user.
     */
    public function remainingBorrowQuota(): int
    {
        $policy = $this->loanPolicy();

        return max(0, $policy->max_borrowed_books - $this->activeLoansCount());
    }
}
