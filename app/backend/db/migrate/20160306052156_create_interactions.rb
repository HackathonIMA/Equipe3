class CreateInteractions < ActiveRecord::Migration
  def change
    create_table :interactions do |t|
      t.reference :user, null: false
      t.reference :share, null: false
      t.boolean :support, null: false

      t.timestamps null: false
    end
  end
end
