package com.group.idm2.Activities;

import android.content.Context;
import android.content.Intent;
import android.widget.Toast;
import java.util.regex.Pattern;
import androidx.appcompat.app.AppCompatActivity;

public class AbstractActivity extends AppCompatActivity {

    public boolean invalidEmail(String email) {
        return !Pattern.matches("^(.+)@(.+)$", email);
    }

    public boolean invalidPassword(String password) {
        return password.length() < 8;
    }

    public void showToast(Context context, String message) {
        Toast.makeText(context,message,Toast.LENGTH_SHORT).show();
    }

    public void signOut() {
        Intent login = new Intent(this, LoginActivity.class);
        login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        login.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(login);
    }
}
