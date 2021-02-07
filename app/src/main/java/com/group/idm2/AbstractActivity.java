package com.group.idm2;


import android.content.Context;
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
}
