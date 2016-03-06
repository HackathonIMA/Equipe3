class CreateInteractions < ActiveRecord::Migration
  def change
    create_table :interactions do |t|
      t.references :user, null: false
      t.references :share, null: false
      t.boolean :support, null: false

      t.timestamps null: false
    end
  end
end
