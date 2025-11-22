class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3',
            'email'    => 'required|email',
        ]);

        $user = auth()->user();

        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diganti!');
    }
}
