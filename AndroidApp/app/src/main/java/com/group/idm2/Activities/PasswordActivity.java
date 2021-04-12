package com.group.idm2.Activities;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import com.group.idm2.R;
import com.group.idm2.Tasks.PasswordTask;

public class PasswordActivity extends DrawerActivity {

    private EditText passwordET, newPasswordET, confirmPasswordET;
    private SharedPreferences sharedPreferences;

    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_password);
        header = "Password Change";
        passwordET = (EditText)findViewById(R.id.passwordET);
        confirmPasswordET = (EditText)findViewById(R.id.confirmPasswordET);
        newPasswordET = (EditText)findViewById(R.id.newPasswordET);
        sharedPreferences = this.getSharedPreferences("preferences",MODE_PRIVATE);
        super.onCreate(savedInstanceState);
    }

    public void update(View view) {
        String old_password = passwordET.getText().toString().trim();
        String confirm_password = confirmPasswordET.getText().toString().trim();
        String new_password = newPasswordET.getText().toString().trim();

        String email = (sharedPreferences.getString("email", ""));
        new PasswordTask(this, email, old_password, new_password, confirm_password).execute();
    }
}
